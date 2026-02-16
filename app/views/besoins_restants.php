<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Besoins Restants - Achats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-header { background-color: #007bff; color: white; }
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; }
        .badge-danger { background-color: #dc3545; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">BNGRC - Gestion des Achats</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/">Accueil</a>
                <a class="nav-link active" href="/besoins-restants">Achats</a>
                <a class="nav-link" href="/achats">Historique</a>
                <a class="nav-link" href="/recap">Récapitulatif</a>
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
                        <h4 class="mb-0">💰 Achats via Dons en Argent</h4>
                        <a href="/config-frais" class="btn btn-light btn-sm">
                            <i class="fas fa-cog"></i> Modifier les frais
                        </a>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0">
                            Utilisez les dons en argent pour acheter des besoins en nature ou matériaux. 
                            Un frais de <strong class="text-primary"><?= number_format($frais ?? 0, 2) ?>%</strong> sera appliqué automatiquement.
                            <a href="/config-frais" class="text-decoration-none">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtre par ville -->
        <div class="row mb-4">
            <div class="col-md-4">
                <form method="GET" action="/besoins-restants">
                    <div class="input-group">
                        <select name="id_ville" class="form-select">
                            <option value="">🏙️ Toutes les villes</option>
                            <?php foreach ($villes as $ville): ?>
                                <option value="<?= $ville['id_ville'] ?>" 
                                    <?= isset($id_ville_selectionnee) && $id_ville_selectionnee == $ville['id_ville'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ville['nom_ville']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Dons en argent disponibles -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">💵 Dons en Argent Disponibles</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($donsArgent)): ?>
                            <p class="text-danger">Aucun don en argent disponible</p>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($donsArgent as $don): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-success">
                                            <div class="card-body">
                                                <h6><?= htmlspecialchars($don['libelle_don']) ?></h6>
                                                <p class="mb-1"><small>Montant initial: <?= number_format($don['montant_initial'], 2) ?> Ar</small></p>
                                                <p class="mb-0"><strong>Restant: <?= number_format($don['reste_argent'], 2) ?> Ar</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des besoins restants -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📦 Besoins Restants</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($besoins)): ?>
                            <p class="text-success">✅ Tous les besoins sont satisfaits !</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Ville</th>
                                            <th>Besoin</th>
                                            <th>Type</th>
                                            <th>Qté Restante</th>
                                            <th>Prix Unit.</th>
                                            <th>Montant Restant</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($besoins as $besoin): ?>
                                            <?php if ($besoin['type_besoin'] !== 'argent'): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($besoin['nom_ville']) ?></td>
                                                    <td><?= htmlspecialchars($besoin['libelle_besoin']) ?></td>
                                                    <td>
                                                        <span class="badge <?= $besoin['type_besoin'] === 'nature' ? 'badge-success' : 'badge-warning' ?>">
                                                            <?= ucfirst($besoin['type_besoin']) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= $besoin['reste'] ?></td>
                                                    <td><?= number_format($besoin['prix_unitaire'], 2) ?> Ar</td>
                                                    <td><strong><?= number_format($besoin['montant_restant'], 2) ?> Ar</strong></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary btn-acheter" 
                                                            data-besoin-id="<?= $besoin['id_besoin'] ?>"
                                                            data-besoin-libelle="<?= htmlspecialchars($besoin['libelle_besoin']) ?>"
                                                            data-besoin-reste="<?= $besoin['reste'] ?>"
                                                            data-besoin-prix="<?= $besoin['prix_unitaire'] ?>">
                                                            🛒 Acheter
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'achat -->
    <div class="modal fade" id="modalAchat" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">🛒 Effectuer un Achat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formAchat">
                        <input type="hidden" id="achat_besoin_id" name="id_besoin">
                        
                        <div class="mb-3">
                            <label class="form-label">Besoin</label>
                            <input type="text" class="form-control" id="achat_besoin_libelle" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Don en Argent</label>
                            <select class="form-select" id="achat_don_id" name="id_don" required>
                                <option value="">Sélectionner un don</option>
                                <?php foreach ($donsArgent as $don): ?>
                                    <option value="<?= $don['id_don'] ?>" data-reste="<?= $don['reste_argent'] ?>">
                                        <?= htmlspecialchars($don['libelle_don']) ?> (<?= number_format($don['reste_argent'], 2) ?> Ar)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantité à acheter</label>
                            <input type="number" class="form-control" id="achat_quantite" name="quantite" min="1" required>
                            <small class="text-muted">Maximum: <span id="achat_max_quantite"></span></small>
                        </div>

                        <div id="simulationResult" class="alert alert-info" style="display:none;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-info" id="btnSimuler">🔍 Simuler</button>
                    <button type="button" class="btn btn-success" id="btnValider" disabled>✅ Valider l'Achat</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const modal = new bootstrap.Modal(document.getElementById('modalAchat'));
        let simulationReussie = false;

        // Ouvrir le modal d'achat
        document.querySelectorAll('.btn-acheter').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('achat_besoin_id').value = this.dataset.besoinId;
                document.getElementById('achat_besoin_libelle').value = this.dataset.besoinLibelle;
                document.getElementById('achat_max_quantite').textContent = this.dataset.besoinReste;
                document.getElementById('achat_quantite').max = this.dataset.besoinReste;
                document.getElementById('achat_quantite').value = 1;
                document.getElementById('simulationResult').style.display = 'none';
                document.getElementById('btnValider').disabled = true;
                simulationReussie = false;
                modal.show();
            });
        });

        // Simuler l'achat
        document.getElementById('btnSimuler').addEventListener('click', async function() {
            const idDon = document.getElementById('achat_don_id').value;
            const idBesoin = document.getElementById('achat_besoin_id').value;
            const quantite = document.getElementById('achat_quantite').value;

            if (!idDon || !quantite) {
                alert('Veuillez remplir tous les champs');
                return;
            }

            try {
                const response = await fetch('/api/achat/simuler', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_don: idDon, id_besoin: idBesoin, quantite: quantite })
                });

                const data = await response.json();
                const resultDiv = document.getElementById('simulationResult');

                if (data.success) {
                    simulationReussie = true;
                    document.getElementById('btnValider').disabled = false;
                    resultDiv.className = 'alert alert-success';
                    resultDiv.innerHTML = `
                        <h6>✅ Simulation Réussie</h6>
                        <p><strong>Besoin:</strong> ${data.simulation.besoin} (${data.simulation.ville})</p>
                        <p><strong>Quantité:</strong> ${data.simulation.quantite}</p>
                        <p><strong>Montant de base:</strong> ${data.simulation.montant_base} Ar</p>
                        <p><strong>Frais (${data.simulation.pourcentage_frais}%):</strong> ${data.simulation.frais} Ar</p>
                        <p><strong>Montant TOTAL:</strong> ${data.simulation.montant_total} Ar</p>
                        <p class="mb-0"><small>Restant du don après: ${data.simulation.montant_restant_don_apres} Ar</small></p>
                    `;
                } else {
                    simulationReussie = false;
                    document.getElementById('btnValider').disabled = true;
                    resultDiv.className = 'alert alert-danger';
                    resultDiv.innerHTML = `<strong>❌ Erreur:</strong> ${data.error}`;
                }
                resultDiv.style.display = 'block';
            } catch (error) {
                alert('Erreur lors de la simulation: ' + error.message);
            }
        });

        // Valider l'achat
        document.getElementById('btnValider').addEventListener('click', async function() {
            if (!simulationReussie) {
                alert('Veuillez d\'abord simuler l\'achat');
                return;
            }

            if (!confirm('Confirmer l\'achat ?')) return;

            const idDon = document.getElementById('achat_don_id').value;
            const idBesoin = document.getElementById('achat_besoin_id').value;
            const quantite = document.getElementById('achat_quantite').value;

            try {
                const response = await fetch('/api/achat/valider', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_don: idDon, id_besoin: idBesoin, quantite: quantite })
                });

                const data = await response.json();

                if (data.success) {
                    alert('✅ ' + data.message);
                    window.location.reload();
                } else {
                    alert('❌ ' + data.error);
                }
            } catch (error) {
                alert('Erreur lors de la validation: ' + error.message);
            }
        });
    </script>
</body>
</html>
