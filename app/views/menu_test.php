<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Principal - Tests BNGRC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 50px 0;
        }
        .menu-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        .menu-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .btn-menu {
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        .btn-menu:hover {
            transform: translateX(10px);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
        }
        .btn-info {
            background: linear-gradient(135deg, #3494e6 0%, #ec6ead 100%);
            border: none;
        }
        .btn-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            color: white;
        }
        .btn-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            border: none;
        }
        .section-title {
            color: white;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .api-section {
            background: rgba(255,255,255,0.9);
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }
        .code-block {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 class="section-title">
                    <i class="fas fa-vials"></i> Menu de Test - Système BNGRC
                </h1>
                <p class="text-white">Sélectionnez une fonctionnalité à tester</p>
            </div>
        </div>

        <div class="row">
            <!-- Pages Web -->
            <div class="col-md-6">
                <div class="menu-card">
                    <div class="text-center">
                        <div class="menu-icon">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <h3>Pages d'Interface</h3>
                        <p class="text-muted">Interfaces utilisateur complètes</p>
                    </div>
                    <hr>
                    
                    <a href="/besoins-restants" class="btn btn-primary btn-menu">
                        <i class="fas fa-shopping-cart"></i> Page des Achats
                        <small class="d-block">Voir et acheter les besoins restants</small>
                    </a>
                    
                    <a href="/achats" class="btn btn-success btn-menu">
                        <i class="fas fa-history"></i> Historique des Achats
                        <small class="d-block">Liste des achats effectués (filtrable)</small>
                    </a>
                    
                    <a href="/recap" class="btn btn-info btn-menu">
                        <i class="fas fa-chart-pie"></i> Récapitulatif Global
                        <small class="d-block">Statistiques et montants (avec Ajax)</small>
                    </a>
                    
                    <a href="/config-frais" class="btn btn-warning btn-menu">
                        <i class="fas fa-cog"></i> Configuration des Frais
                        <small class="d-block">Définir le % de frais d'achat</small>
                    </a>

                    <a href="/" class="btn btn-secondary btn-menu">
                        <i class="fas fa-home"></i> Accueil Principal
                        <small class="d-block">Retour à la page d'accueil</small>
                    </a>
                </div>
            </div>

            <!-- API Endpoints -->
            <div class="col-md-6">
                <div class="menu-card">
                    <div class="text-center">
                        <div class="menu-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <h3>API REST (JSON)</h3>
                        <p class="text-muted">Tester les endpoints API</p>
                    </div>
                    <hr>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Utilisez des outils comme:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Postman</li>
                            <li>Insomnia</li>
                            <li>curl</li>
                            <li>Console JavaScript (fetch)</li>
                        </ul>
                    </div>

                    <h5 class="mt-3"><i class="fas fa-plug"></i> Endpoints disponibles:</h5>
                    
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark">POST</span>
                        <code>/api/achat/simuler</code>
                        <p class="small text-muted mb-0">Simuler un achat sans l'enregistrer</p>
                    </div>
                    
                    <div class="mb-3">
                        <span class="badge bg-success">POST</span>
                        <code>/api/achat/valider</code>
                        <p class="small text-muted mb-0">Valider et enregistrer un achat</p>
                    </div>
                    
                    <div class="mb-3">
                        <span class="badge bg-primary">GET</span>
                        <code>/api/recap</code>
                        <p class="small text-muted mb-0">Récupérer le récapitulatif global</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Exemples d'utilisation API -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="api-section">
                    <h4><i class="fas fa-code"></i> Exemples d'utilisation API</h4>
                    
                    <h5 class="mt-4">1. Simuler un achat (POST /api/achat/simuler)</h5>
                    <div class="code-block">
fetch('/api/achat/simuler', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        id_don: 1,
        id_besoin: 2,
        quantite: 100
    })
})
.then(res => res.json())
.then(data => console.log(data));
                    </div>

                    <h5 class="mt-4">2. Valider un achat (POST /api/achat/valider)</h5>
                    <div class="code-block">
fetch('/api/achat/valider', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        id_don: 1,
        id_besoin: 2,
        quantite: 100
    })
})
.then(res => res.json())
.then(data => console.log(data));
                    </div>

                    <h5 class="mt-4">3. Récupérer le récapitulatif (GET /api/recap)</h5>
                    <div class="code-block">
fetch('/api/recap')
    .then(res => res.json())
    .then(data => console.log(data));
                    </div>

                    <h5 class="mt-4">4. Exemple avec curl</h5>
                    <div class="code-block">
curl -X POST http://localhost:8000/api/achat/simuler \
  -H "Content-Type: application/json" \
  -d '{"id_don":1,"id_besoin":2,"quantite":100}'
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions SQL -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="api-section">
                    <h4><i class="fas fa-database"></i> Configuration de la Base de Données</h4>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Avant de tester, exécutez ces scripts SQL:</strong>
                    </div>

                    <ol>
                        <li class="mb-2">
                            <strong>Créer les vues:</strong>
                            <div class="code-block mt-2">
mysql -u root -p BNGRC < base/sujet1_16/02/V3_views.sql
                            </div>
                        </li>
                        <li class="mb-2">
                            <strong>Initialiser la configuration des frais:</strong>
                            <div class="code-block mt-2">
mysql -u root -p BNGRC < base/sujet1_16/02/V4_config_frais.sql
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
