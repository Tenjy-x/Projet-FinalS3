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

    function createDon() {
        $description = Flight::request()->data->description;
        $type = Flight::request()->data->type;
        $quantites = Flight::request()->data->quantites;
        $model = new DonModel(Flight::db());
        try {
            $model->createDon($description, $type, $quantites);
            return ['success' => 'Don ajouté avec succès !'];
        } catch (\Exception $e) {
            return ['error' => 'Erreur lors de l\'ajout du don : ' . $e->getMessage()];
        }
    }
}