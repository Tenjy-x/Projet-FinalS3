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
        // Récupérer toutes les villes et types pour le dropdown
        $model = new AllModels($this->app->db());
        $villes = $model->getAllVilles();
        $types = $model->getAllTypes();

        // Rendre la vue avec le layout Modal
        $this->app->render('Modal', [
            'page' => 'besoin',
            'villes' => $villes,
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
            $libelle = $this->app->request()->data->libelle_besoin ?? '';
            $type = $this->app->request()->data->type_besoin ?? '';
            $quantite = $this->app->request()->data->quantite ?? 0;
            $prix_unitaire = $this->app->request()->data->prix_unitaire ?? 0;
            $id_ville = $this->app->request()->data->id_ville ?? null;

            // Validation basique
            if (empty($libelle) || empty($type) || $quantite <= 0 || $prix_unitaire <= 0 || empty($id_ville)) {
                $this->app->redirect('/besoin?error=' . urlencode('Tous les champs sont obligatoires'));
                return;
            }

            // Valider le type_besoin
            $types_valides = ['nature', 'materiaux', 'argent'];
            if (!in_array($type, $types_valides, true)) {
                $this->app->redirect('/besoin?error=' . urlencode('Type de besoin invalide'));
                return;
            }

            // Préparer les données pour l'insertion
            $data = [
                $libelle,
                $type,
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
