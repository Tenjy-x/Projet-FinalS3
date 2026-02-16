<?php

use app\controllers\ApiExampleController;
use app\controllers\BesoinController;
use app\controllers\BngrcController;
use app\controllers\StatsController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

// This wraps all routes in the group with the SecurityHeadersMiddleware
$router->group('', function(Router $router) use ($app) {
	$router->get('/', function() use ($app) {
		$controller = new StatsController();
		$villes = $controller->getAllVilles();
		$besoins = $controller->getAllBesoin();
		$dons = $controller->getAllDons();
		$app->render('Modal', [ 'page' => 'welcome' , 'villes' => $villes , 'besoins' => $besoins , 'dons' => $dons]);
	});


<<<<<<< HEAD
	$router->group('/api', function() use ($router) {
		$router->get('/users', [ ApiExampleController::class, 'getUsers' ]);
		$router->get('/users/@id:[0-9]', [ ApiExampleController::class, 'getUser' ]);
		$router->post('/users/@id:[0-9]', [ ApiExampleController::class, 'updateUser' ]);
	});

<<<<<<< HEAD
=======
=======
	
	$router->get('/', function() use ($app) {
		$controller = new StatsController();
		$ville = $controller->getAllVilles();
		$app->render('Modal' , ['page' => 'Modal' , 'villes' => $ville]);
	});

	// Routes pour la gestion des besoins
	$router->get('/besoin', [ BesoinController::class, 'showForm' ]);
	$router->post('/besoin/insert', [ BesoinController::class, 'insertBesoin' ]);
>>>>>>> 5dafc4c (commt farany)
	
>>>>>>> d2ec5ca (don_ary_e)
}, [ SecurityHeadersMiddleware::class ]);