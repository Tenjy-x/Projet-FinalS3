<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration des Frais - BNGRC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .config-card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            border: none;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 20px;
        }
        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 40px;
            font-weight: 600;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
        }
        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .example-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-cog"></i> BNGRC - Configuration
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/">Accueil</a>
                <a class="nav-link" href="/besoins-restants">Achats</a>
                <a class="nav-link" href="/achats">Historique</a>
                <a class="nav-link" href="/recap">Récapitulatif</a>
                <a class="nav-link active" href="/config-frais">
                    <i class="fas fa-cog"></i> Configuration
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Messages de succès/erreur -->
                <?php if (isset($success) && $success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> Configuration enregistrée avec succès !
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Carte de configuration -->
                <div class="card config-card">
                    <div class="card-header">
                        <h3 class="mb-0">
                            <i class="fas fa-percentage"></i> Configuration des Frais d'Achat
                        </h3>
                        <p class="mb-0 mt-2 opacity-75">
                            Définissez le pourcentage de frais appliqué lors des achats via dons en argent
                        </p>
                    </div>
                    <div class="card-body p-4">
                        <!-- Information -->
                        <div class="info-box">
                            <h6><i class="fas fa-info-circle"></i> Information</h6>
                            <p class="mb-0">
                                Les frais d'achat sont appliqués automatiquement lorsqu'un don en argent est utilisé 
                                pour acheter des besoins en nature ou matériaux. Ces frais couvrent les coûts de gestion 
                                et de logistique.
                            </p>
                        </div>

                        <!-- Formulaire -->
                        <form method="POST" action="/config-frais" id="configForm">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label for="frais" class="form-label fw-bold">
                                        <i class="fas fa-percentage"></i> Pourcentage de Frais (%)
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <input 
                                            type="number" 
                                            class="form-control" 
                                            id="frais" 
                                            name="frais" 
                                            value="<?= number_format($frais ?? 0, 2, '.', '') ?>"
                                            min="0" 
                                            max="100" 
                                            step="0.01" 
                                            required
                                            placeholder="Ex: 10.00"
                                        >
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Entrez un nombre entre 0 et 100</small>
                                </div>
                            </div>

                            <!-- Exemple de calcul -->
                            <div class="example-box mb-4">
                                <h6><i class="fas fa-calculator"></i> Exemple de Calcul</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Montant de base:</strong> 1 000 Ar</p>
                                        <p class="mb-1"><strong>Frais (<span id="exampleFrais"><?= number_format($frais ?? 0, 2) ?></span>%):</strong> 
                                            <span id="calculFrais"><?= number_format((($frais ?? 0) / 100) * 1000, 2) ?></span> Ar
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1 text-success fw-bold">
                                            <strong>Total à payer:</strong> 
                                            <span id="calculTotal"><?= number_format(1000 + (($frais ?? 0) / 100) * 1000, 2) ?></span> Ar
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-save btn-lg">
                                    <i class="fas fa-save"></i> Enregistrer la Configuration
                                </button>
                                <a href="/besoins-restants" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour aux Achats
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Informations complémentaires -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-question-circle"></i> Comment ça fonctionne ?
                        </h5>
                        <ol class="mb-0">
                            <li class="mb-2">
                                <strong>Configuration:</strong> Vous définissez ici le pourcentage de frais à appliquer.
                            </li>
                            <li class="mb-2">
                                <strong>Application:</strong> Lors d'un achat, les frais sont calculés automatiquement 
                                sur le montant de base (quantité × prix unitaire).
                            </li>
                            <li class="mb-2">
                                <strong>Déduction:</strong> Le montant total (montant de base + frais) est déduit 
                                du don en argent utilisé.
                            </li>
                            <li>
                                <strong>Modification:</strong> Vous pouvez modifier ce pourcentage à tout moment. 
                                La nouvelle valeur s'appliquera aux futurs achats.
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mise à jour dynamique de l'exemple
        document.getElementById('frais').addEventListener('input', function() {
            const frais = parseFloat(this.value) || 0;
            const montantBase = 1000;
            const montantFrais = (montantBase * frais) / 100;
            const total = montantBase + montantFrais;
            
            document.getElementById('exampleFrais').textContent = frais.toFixed(2);
            document.getElementById('calculFrais').textContent = montantFrais.toFixed(2);
            document.getElementById('calculTotal').textContent = total.toFixed(2);
        });

        // Validation du formulaire
        document.getElementById('configForm').addEventListener('submit', function(e) {
            const frais = parseFloat(document.getElementById('frais').value);
            
            if (frais < 0 || frais > 100) {
                e.preventDefault();
                alert('Les frais doivent être entre 0 et 100%');
                return false;
            }
        });

        // Auto-hide alerts après 5 secondes
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>
