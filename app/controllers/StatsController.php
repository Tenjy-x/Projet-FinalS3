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
}
