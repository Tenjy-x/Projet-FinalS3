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
            'types' => $types,
            'success' => $this->app->request()->query->success ?? null,
            'error' => $this->app->request()->query->error ?? null
        ]);
    }

    /**
     * Traite l'insertion d'un nouveau besoin
     */
    public function insertBesoin()
    {
        try {
            // Récupérer les données du formulaire
            $nom_produit = trim($this->app->request()->data->nom_produit ?? '');
            $id_type = (int) ($this->app->request()->data->id_type ?? 0);
            $quantite = $this->app->request()->data->quantite ?? 0;
            $prix_unitaire = $this->app->request()->data->prix_unitaire ?? 0;
            $id_ville = $this->app->request()->data->id_ville ?? null;

            // Validation basique
            if (empty($nom_produit) || $id_type <= 0 || $quantite <= 0 || $prix_unitaire <= 0 || empty($id_ville)) {
                $this->app->redirect('/besoin?error=' . urlencode('Tous les champs sont obligatoires'));
                return;
            }

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
            $model = new AllModels($this->app->db());
            $result = $model->insertBesoin($data);

            if ($result) {
                $this->app->redirect('/besoin?success=' . urlencode('Besoin ajouté avec succès'));
            } else {
                $this->app->redirect('/besoin?error=' . urlencode('Erreur lors de l\'ajout du besoin'));
            }
        } catch (\Exception $e) {
            $this->app->redirect('/besoin?error=' . urlencode($e->getMessage()));
        }
    }
}
