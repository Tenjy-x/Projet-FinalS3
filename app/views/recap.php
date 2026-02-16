<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif Global - BNGRC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-header { background-color: #007bff; color: white; }
        .stat-card { border-left: 5px solid; }
        .stat-card.total { border-left-color: #007bff; }
        .stat-card.satisfait { border-left-color: #28a745; }
        .stat-card.restant { border-left-color: #dc3545; }
        .progress-bar-animated { animation: progress-bar-stripes 1s linear infinite; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">BNGRC - Récapitulatif</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/">Accueil</a>
                <a class="nav-link" href="/besoins-restants">Achats</a>
                <a class="nav-link" href="/achats">Historique</a>
                <a class="nav-link active" href="/recap">Récapitulatif</a>
                <a class="nav-link" href="/config-frais">
                    <i class="fas fa-cog"></i> Configuration
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">📊 Récapitulatif Global des Besoins</h4>
                        <button class="btn btn-light btn-sm" id="btnActualiser">
                            🔄 Actualiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="recapContent">
            <div class="row mb-4">
                <!-- Montant Total -->
                <div class="col-md-4">
                    <div class="card stat-card total">
                        <div class="card-body">
                            <h6 class="text-muted">💰 Montant Total des Besoins</h6>
                            <h2 class="mb-0" id="montantTotal">
                                <?= number_format($recap['montant_total'] ?? 0, 2) ?> Ar
                            </h2>
                        </div>
                    </div>
                </div>

                <!-- Montant Satisfait -->
                <div class="col-md-4">
                    <div class="card stat-card satisfait">
                        <div class="card-body">
                            <h6 class="text-muted">✅ Montant Satisfait</h6>
                            <h2 class="mb-0 text-success" id="montantSatisfait">
                                <?= number_format($recap['montant_satisfait'] ?? 0, 2) ?> Ar
                            </h2>
                        </div>
                    </div>
                </div>

                <!-- Montant Restant -->
                <div class="col-md-4">
                    <div class="card stat-card restant">
                        <div class="card-body">
                            <h6 class="text-muted">⏳ Montant Restant</h6>
                            <h2 class="mb-0 text-danger" id="montantRestant">
                                <?= number_format($recap['montant_restant'] ?? 0, 2) ?> Ar
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barre de progression -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Progression de Satisfaction</h5>
                            <?php 
                                $total = $recap['montant_total'] ?? 0;
                                $satisfait = $recap['montant_satisfait'] ?? 0;
                                $pourcentage = $total > 0 ? ($satisfait / $total) * 100 : 0;
                            ?>
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                     role="progressbar" 
                                     style="width: <?= round($pourcentage, 2) ?>%"
                                     id="progressBar">
                                    <strong id="pourcentageText"><?= round($pourcentage, 2) ?>%</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails en tableau -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">📋 Détails</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Catégorie</th>
                                        <th class="text-end">Montant (Ar)</th>
                                        <th class="text-end">Pourcentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-primary">
                                        <td><strong>Total des Besoins</strong></td>
                                        <td class="text-end"><strong id="detailTotal"><?= number_format($recap['montant_total'] ?? 0, 2) ?></strong></td>
                                        <td class="text-end">100%</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>Satisfait</td>
                                        <td class="text-end text-success" id="detailSatisfait"><?= number_format($recap['montant_satisfait'] ?? 0, 2) ?></td>
                                        <td class="text-end text-success" id="detailPourcentageSatisfait"><?= round($pourcentage, 2) ?>%</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>Restant</td>
                                        <td class="text-end text-danger" id="detailRestant"><?= number_format($recap['montant_restant'] ?? 0, 2) ?></td>
                                        <td class="text-end text-danger" id="detailPourcentageRestant"><?= round(100 - $pourcentage, 2) ?>%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loader -->
        <div id="loader" style="display:none;" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2">Mise à jour en cours...</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        async function actualiserRecap() {
            const loader = document.getElementById('loader');
            const content = document.getElementById('recapContent');
            const btn = document.getElementById('btnActualiser');

            try {
                btn.disabled = true;
                btn.innerHTML = '⏳ Chargement...';
                loader.style.display = 'block';
                content.style.opacity = '0.5';

                const response = await fetch('/api/recap');
                const data = await response.json();

                if (data.success) {
                    const recap = data.recap;

                    // Mise à jour des cartes
                    document.getElementById('montantTotal').textContent = 
                        formatNumber(recap.montant_total) + ' Ar';
                    document.getElementById('montantSatisfait').textContent = 
                        formatNumber(recap.montant_satisfait) + ' Ar';
                    document.getElementById('montantRestant').textContent = 
                        formatNumber(recap.montant_restant) + ' Ar';

                    // Mise à jour de la barre de progression
                    const pourcentage = recap.pourcentage_satisfait;
                    const progressBar = document.getElementById('progressBar');
                    progressBar.style.width = pourcentage + '%';
                    document.getElementById('pourcentageText').textContent = pourcentage + '%';

                    // Mise à jour du tableau
                    document.getElementById('detailTotal').textContent = formatNumber(recap.montant_total);
                    document.getElementById('detailSatisfait').textContent = formatNumber(recap.montant_satisfait);
                    document.getElementById('detailRestant').textContent = formatNumber(recap.montant_restant);
                    document.getElementById('detailPourcentageSatisfait').textContent = pourcentage + '%';
                    document.getElementById('detailPourcentageRestant').textContent = 
                        (100 - pourcentage).toFixed(2) + '%';

                    // Animation de succès
                    content.style.transition = 'opacity 0.3s';
                    content.style.opacity = '1';
                    
                    // Notification
                    showNotification('✅ Récapitulatif actualisé avec succès!', 'success');
                } else {
                    showNotification('❌ Erreur: ' + data.error, 'danger');
                }
            } catch (error) {
                showNotification('❌ Erreur de connexion: ' + error.message, 'danger');
            } finally {
                loader.style.display = 'none';
                content.style.opacity = '1';
                btn.disabled = false;
                btn.innerHTML = '🔄 Actualiser';
            }
        }

        function formatNumber(num) {
            return parseFloat(num).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }

        function showNotification(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
            alertDiv.style.zIndex = 9999;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            setTimeout(() => alertDiv.remove(), 3000);
        }

        // Event listener pour le bouton actualiser
        document.getElementById('btnActualiser').addEventListener('click', actualiserRecap);

        // Auto-actualisation toutes les 10 secondes
        setInterval(actualiserRecap, 10000);
    </script>
</body>
</html>
