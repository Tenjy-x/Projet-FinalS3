<?php
namespace app\controllers;
use app\Allmodels;
use flight\Engine;
use Flight;

class StatsController {
    function getAllVilles() {
        $model = new Allmodels(Flight::db());
        $villes = $model->getAllVilles();
        return $villes;
    }
    
    function getAllBesoin() {
        $model = new Allmodels(Flight::db());
        $besoins = $model->getAllBesoin();
        return $besoins;
    }
}
