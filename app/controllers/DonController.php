<?php
namespace app\controllers;
use app\Allmodels;
use flight\Engine;
use Flight;

class DonController {
    function getAlltypes() {
        $model = new Allmodels(Flight::db());
        $types = $model->getAllTypes();
        return $types;
    }

    function createDon() {
        $description = Flight::request()->data->description;
        $type = Flight::request()->data->type;
        $quantites = Flight::request()->data->quantites;
        $date = Flight::request()->data->date;
        $model = new Allmodels(Flight::db());
        $model->createDon($description, $type, $quantites, $date);
        Flight::redirect('/Don');
    }
}