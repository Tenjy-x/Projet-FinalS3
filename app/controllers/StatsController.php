<?php
namespace app\controllers;
use app\models\AllModels;
use flight\Engine;
use Flight;

class StatsController {
    function getAllVilles() {
        $model = new AllModels(Flight::db());
        $villes = $model->getAllVilles();
        return $villes;
    }
    
<<<<<<< HEAD
    function getAllBesoins() {
        $model = new Allmodels(Flight::db());
=======
    function getAllBesoin() {
        $model = new AllModels(Flight::db());
>>>>>>> 5dafc4c (commt farany)
        $besoins = $model->getAllBesoin();
        return $besoins;
    }
}
