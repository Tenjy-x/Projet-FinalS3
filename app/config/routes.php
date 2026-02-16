<?php
use app\controllers\StatsController;
use app\controllers\ApiExampleController;
use app\controllers\BesoinController;
use app\controllers\BngrcController;
use app\controllers\DonController;
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
		$besoins = $controller->getAllBesoins();
		$dons = $controller->getAllDons();
		$app->render('Modal', [ 'page' => 'welcome' , 'villes' => $villes , 'besoins' => $besoins , 'dons' => $dons]);
	});

	$router->get('/dons', function() use ($app) {
		$controller = new StatsController();
		$donController = new DonController();
		$villes = $controller->getAllVilles();
		$besoins = $controller->getAllBesoins();
		$dons = $controller->getAllDons();
		$type = $donController->getAlltypes();
		$app->render('Modal', [ 'page' => 'Don' , 'villes' => $villes , 'besoins' => $besoins , 'dons' => $dons, 'type' => $type]);
	});

	$router->post('/dons', function() use ($app) {
		$donController = new DonController();
		$result = $donController->createDon();
		$controller = new StatsController();
		$villes = $controller->getAllVilles();
		$besoins = $controller->getAllBesoins();
		$dons = $controller->getAllDons();
		$type = $donController->getAlltypes();
		$data = [ 'page' => 'Don', 'villes' => $villes, 'besoins' => $besoins, 'dons' => $dons, 'type' => $type ];
		if (isset($result['success'])) {
			$data['success'] = $result['success'];
		}
		if (isset($result['error'])) {
			$data['error'] = $result['error'];
		}
		$app->render('Modal', $data);
	});

	$router->get('/besoin', [ BesoinController::class, 'showForm' ]);
	
	$router->post('/besoin', [ BesoinController::class, 'insertBesoin' ]);

	$router->get('/bord', function() use ($app) {
		$controller = new StatsController();
		$villes = $controller->getAllVilles();
		$besoins = $controller->getAllBesoins();
		$dons = $controller->getAllDons();
		$villesBesoins = $controller->getVillesBesoins();
		$app->render('Modal', [ 'page' => 'Bord' , 'villes' => $villes , 'besoins' => $besoins , 'dons' => $dons , 'villesBesoins' => $villesBesoins]);
	});
	
	$router->group('/api', function() use ($router) {
		$router->get('/users', [ ApiExampleController::class, 'getUsers' ]);
		$router->get('/users/@id:[0-9]', [ ApiExampleController::class, 'getUser' ]);
		$router->post('/users/@id:[0-9]', [ ApiExampleController::class, 'updateUser' ]);
	});



}, [ SecurityHeadersMiddleware::class ]);