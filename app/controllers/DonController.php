<?php
namespace app\controllers;
use app\models\DonModel;
use flight\Engine;
use Flight;

class DonController {
    function getAlltypes() {
        $model = new DonModel(Flight::db());
        $types = $model->getAllTypes();
        return $types;
    }

    function getAllProduits() {
        $model = new DonModel(Flight::db());
        return $model->getAllProduits();
    }

    function createDon() {
        $description = Flight::request()->data->description;
        $nom_produit = trim(Flight::request()->data->nom_produit ?? '');
        $id_type = (int) (Flight::request()->data->id_type ?? 0);
        $quantites = Flight::request()->data->quantites;
        $model = new DonModel(Flight::db());
        try {
            if (empty($nom_produit) || $id_type <= 0 || $quantites <= 0) {
                return ['error' => 'Tous les champs sont obligatoires'];
            }

            // Chercher si le produit existe déjà
            $produit = $model->findProduitByName($nom_produit);
            if ($produit) {
                $id_produit = $produit['id_produit'];
            } else {
                // Créer le nouveau produit
                $id_produit = $model->createProduit(ucfirst($nom_produit), $id_type);
            }

            $model->createDon($description, $id_produit, $quantites);
            return ['success' => 'Don ajouté avec succès !'];
        } catch (\Exception $e) {
            return ['error' => 'Erreur lors de l\'ajout du don : ' . $e->getMessage()];
        }
    }
}