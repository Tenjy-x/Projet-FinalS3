<?php
use app\controllers\StatsController;
use app\controllers\DispatchController;
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

	$router->get('/dispatch', function() use ($app) {
		$dispatchController = new DispatchController();
		$dispatchController->dispatch();
		// Une fois le dispatch effectué, on revient sur le tableau de bord complet
		$app->redirect('/bord');
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
		
		$dashboardData = $controller->getDashboardData();
		$stats = $controller->getStatsGlobales();
		$donsEnAttente = $controller->getDonsEnAttente();
		$besoinsUrgents = $controller->getBesoinsUrgents(3);
		
		$villes = [];
		foreach ($dashboardData as $row) {
			$ville_id = $row['id_ville'];
			if (!isset($villes[$ville_id])) {
				$villes[$ville_id] = [
					'id_ville' => $row['id_ville'],
					'nom_ville' => $row['nom_ville'],
					'besoins' => []
				];
			}
			if ($row['quantite_recue'] == 0) {
				$row['statut'] = 'urgent';
			} elseif ($row['quantite_recue'] >= $row['quantite_besoin']) {
				$row['statut'] = 'complet';
			} else {
				$row['statut'] = 'partiel';
			}
			$villes[$ville_id]['besoins'][] = $row;
		}
		
		foreach ($villes as &$ville) {
			foreach ($ville['besoins'] as &$besoin) {
				$besoin['attributions'] = $controller->getAttributionsParBesoin($besoin['id_besoin']);
			}
		}
		
		$app->render('Modal', [ 
			'page' => 'Bord',
			'villes' => $villes,
			'stats' => $stats,
			'donsEnAttente' => $donsEnAttente,
			'besoinsUrgents' => $besoinsUrgents
		]);
	});
	
	$router->group('/api', function() use ($router) {
		$router->get('/users', [ ApiExampleController::class, 'getUsers' ]);
		$router->get('/users/@id:[0-9]', [ ApiExampleController::class, 'getUser' ]);
		$router->post('/users/@id:[0-9]', [ ApiExampleController::class, 'updateUser' ]);
		
		// API pour les achats
		$router->post('/achat/simuler', [ DispatchController::class, 'simulerAchat' ]);
		$router->post('/achat/valider', [ DispatchController::class, 'validerAchat' ]);
		$router->get('/recap', [ DispatchController::class, 'getRecapGlobal' ]);
	});

	// Pages pour les achats et récapitulation
	$router->get('/besoins-restants', [ DispatchController::class, 'pageBesoinsRestants' ]);
	$router->get('/recap', [ DispatchController::class, 'pageRecap' ]);
	$router->get('/achats', [ DispatchController::class, 'pageAchats' ]);
	
	// Page de configuration des frais
	$router->get('/config-frais', [ DispatchController::class, 'pageConfigFrais' ]);
	$router->post('/config-frais', [ DispatchController::class, 'pageConfigFrais' ]);

	// Page de menu de test
	$router->get('/menu-test', function() use ($app) {
		$app->render('menu_test');
	});



}, [ SecurityHeadersMiddleware::class ]);
