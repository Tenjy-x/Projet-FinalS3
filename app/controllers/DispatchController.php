<?php
namespace app\controllers;

use app\models\DispatchModel;
use Flight;

class DispatchController
{
    public function dispatch()
    {
        $model = new DispatchModel(Flight::db());
        $result = $model->dispatchDons();

        Flight::json([
            'status' => 'ok',
            'attributions' => $result['attributions'],
            'quantite' => $result['quantite']
        ]);
    }
}