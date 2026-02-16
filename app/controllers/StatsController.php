<?php
namespace app\controllers;
use app\models\Allmodels;
use flight\Engine;
use Flight;

class StatsController {
    function getAllVilles() {
        $model = new Allmodels(Flight::db());
        $villes = $model->getAllVilles();
        return $villes;
    }
    
    function getAllBesoins() {
        $model = new Allmodels(Flight::db());
        $besoins = $model->getAllBesoin();
        return $besoins;
    }

    function getAllDons() {
        $model = new Allmodels(Flight::db());
        $dons = $model->getAllDon();
        return $dons;
    }

    function getVillesBesoins() {
        $model =  new Allmodels(Flight::db());
        $villesBesoins = $model->getVillesBesoins();
        return $villesBesoins;
    }
}
