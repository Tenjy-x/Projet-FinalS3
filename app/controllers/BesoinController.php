<?php

namespace app\controllers;

use app\models\Allmodels;
use flight\Engine;

class BesoinController
{
    protected Engine $app;

    public function __construct(Engine $app)
    {
        $this->app = $app;
    }

    /**
     * Affiche le formulaire d'ajout de besoin
     */
    public function showForm()
    {
        // Récupérer toutes les villes, produits et types pour le formulaire
        $model = new AllModels($this->app->db());
        $villes = $model->getAllVilles();
        $produits = $model->getAllProduits();
        $types = $model->getAllTypes();

        // Rendre la vue avec le layout Modal
        $this->app->render('Modal', [
            'page' => 'besoin',
            'villes' => $villes,
            'produits' => $produits,
            'types' => $types
        ]);
    }

    /**
     * Traite l'insertion d'un nouveau besoin
     */
    public function insertBesoin()
    {
        $model = new AllModels($this->app->db());
        $message = [];

        try {
            // Récupérer les données du formulaire
            $nom_produit = trim($this->app->request()->data->nom_produit ?? '');
            $id_type = (int) ($this->app->request()->data->id_type ?? 0);
            $quantite = $this->app->request()->data->quantite ?? 0;
            $prix_unitaire = $this->app->request()->data->prix_unitaire ?? 0;
            $id_ville = $this->app->request()->data->id_ville ?? null;

            // Validation basique
            if (empty($nom_produit) || $id_type <= 0 || $quantite <= 0 || $prix_unitaire <= 0 || empty($id_ville)) {
                $message['error'] = 'Tous les champs sont obligatoires';
            } else {
                // Chercher ou créer le produit
                $donModel = new \app\models\DonModel($this->app->db());
                $produit = $donModel->findProduitByName($nom_produit);
                if ($produit) {
                    $id_produit = $produit['id_produit'];
                } else {
                    $id_produit = $donModel->createProduit(ucfirst($nom_produit), $id_type);
                }

                // Préparer les données pour l'insertion
                $data = [
                    (int) $id_produit,
                    (int) $quantite,
                    (float) $prix_unitaire,
                    (int) $id_ville
                ];

                // Insérer dans la base de données
                $result = $model->insertBesoin($data);

                if ($result) {
                    $message['success'] = 'Besoin ajouté avec succès';
                } else {
                    $message['error'] = 'Erreur lors de l\'ajout du besoin';
                }
            }
        } catch (\Exception $e) {
            $message['error'] = $e->getMessage();
        }

        // Recharger les données pour le formulaire et rendre la vue directement
        $villes = $model->getAllVilles();
        $produits = $model->getAllProduits();
        $types = $model->getAllTypes();

        $viewData = [
            'page' => 'besoin',
            'villes' => $villes,
            'produits' => $produits,
            'types' => $types
        ];

        if (isset($message['success'])) {
            $viewData['success'] = $message['success'];
        }
        if (isset($message['error'])) {
            $viewData['error'] = $message['error'];
        }

        $this->app->render('Modal', $viewData);
    }
}
