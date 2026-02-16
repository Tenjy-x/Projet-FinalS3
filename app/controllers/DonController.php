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
        $date = Flight::request()->data->date;
        $model = new DonModel(Flight::db());
        $model->createDon($description, $type, $quantites, $date);
        Flight::redirect('/dons');
    }
}