<?php
namespace app\controllers;
use app\models\Allmodels;
use flight\Engine;
use Flight;

class StatsController {
    function getAllVilles() {
        $model = new AllModels(Flight::db());
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

    function getAttributionDetails() {
        $model = new Allmodels(Flight::db());
        $attributions = $model->getAttributionDetails();
        return $attributions;
    }

    function getDashboardData() {
        $model = new Allmodels(Flight::db());
        return $model->getDashboardData();
    }

    function getStatsGlobales() {
        $model = new Allmodels(Flight::db());
        return $model->getStatsGlobales();
    }

    function getAttributionsParBesoin($id_besoin) {
        $model = new Allmodels(Flight::db());
        return $model->getAttributionsParBesoin($id_besoin);
    }

    function getDonsEnAttente() {
        $model = new Allmodels(Flight::db());
        return $model->getDonsEnAttente();
    }

    function getBesoinsUrgents($jours = 3) {
        $model = new Allmodels(Flight::db());
        return $model->getBesoinsUrgents($jours);
    }
}
