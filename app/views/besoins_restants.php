<!-- Page Achats via Dons en Argent -->
<div class="container-fluid" style="margin-top: 170px;">

    <!-- En-tête + Config frais -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #20212B; color: #ffffff;">
                    <h4 class="mb-0">Achats via Dons en Argent</h4>
                    <button class="btn btn-sm" style="border: 1px solid #FDBE33; color: #FDBE33;" data-toggle="modal" data-target="#modalFrais">
                        <i class="fas fa-cog"></i> Modifier les frais
                    </button>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">
                        Utilisez les dons en argent pour acheter des besoins en nature ou matériaux.
                        Un frais de <strong style="color: #FDBE33;"><?= number_format($frais ?? 0, 2) ?>%</strong> sera appliqué automatiquement.
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
                    <select name="id_ville" class="custom-select">
                        <option value="">Toutes les villes</option>
                        <?php foreach ($villes as $ville): ?>
                            <option value="<?= $ville['id_ville'] ?>"
                                <?= isset($id_ville_selectionnee) && $id_ville_selectionnee == $ville['id_ville'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ville['nom_ville']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-custom">Filtrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Dons en argent disponibles -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #4a4c70; color: #ffffff;">
                    <h5 class="mb-0">Dons en Argent Disponibles</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($donsArgent)): ?>
                        <p class="text-danger mb-0">Aucun don en argent disponible.</p>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($donsArgent as $don): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card" style="border-left: 4px solid #FDBE33;">
                                        <div class="card-body">
                                            <h6 style="color: #4a4c70;"><?= htmlspecialchars($don['libelle_don']) ?></h6>
                                            <p class="mb-1"><small class="text-muted">Montant initial: <?= number_format($don['montant_initial'], 2) ?> Ar</small></p>
                                            <p class="mb-0"><strong style="color: #FDBE33;">Restant: <?= number_format($don['reste_argent'], 2) ?> Ar</strong></p>
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
                <div class="card-header" style="background-color: #20212B; color: #ffffff;">
                    <h5 class="mb-0">Besoins Restants</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($besoins)): ?>
                        <div class="alert alert-success">✅ Tous les besoins sont satisfaits !</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead style="background-color: #4a4c70; color: #ffffff;">
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
                                        <?php if ($besoin['nom_type'] !== 'argent'): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($besoin['nom_ville']) ?></td>
                                                <td><?= htmlspecialchars($besoin['libelle_besoin']) ?></td>
                                                <td>
                                                    <span class="badge" style="background-color: <?= $besoin['nom_type'] === 'nature' ? '#28a745' : '#FDBE33' ?>; color: #fff;">
                                                        <?= ucfirst($besoin['nom_type']) ?>
                                                    </span>
                                                </td>
                                                <td><?= $besoin['reste'] ?></td>
                                                <td><?= number_format($besoin['prix_unitaire'], 2) ?> Ar</td>
                                                <td><strong><?= number_format($besoin['montant_restant'], 2) ?> Ar</strong></td>
                                                <td>
                                                    <button class="btn btn-sm btn-custom btn-acheter"
                                                        data-toggle="modal" data-target="#modalAchat"
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

    <!-- Boutons d'action -->
    <div class="row mt-4 mb-5">
        <div class="col-md-12 text-center">
            <a href="/achats" class="btn btn-custom btn-lg">
                Voir les Achats Effectués
            </a>
        </div>
    </div>
</div>

<!-- Modal Configuration des Frais -->
<div class="modal fade" id="modalFrais" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #20212B; color: #ffffff;">
                <h5 class="modal-title">Configuration des Frais d'Achat</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="/achats/frais">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="frais_achat">Pourcentage de frais (%)</label>
                        <input type="number" step="0.01" class="form-control" id="frais_achat" name="frais_achat"
                               value="<?= htmlspecialchars($frais ?? 10) ?>" min="0" max="100" required>
                        <small class="form-text text-muted">Ce pourcentage sera appliqué automatiquement sur chaque achat.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-custom">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'achat -->
<div class="modal fade" id="modalAchat" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #20212B; color: #ffffff;">
                <h5 class="modal-title">Effectuer un Achat</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAchat">
                    <input type="hidden" id="achat_besoin_id" name="id_besoin">

                    <div class="form-group">
                        <label for="achat_besoin_libelle">Besoin</label>
                        <input type="text" class="form-control" id="achat_besoin_libelle" readonly>
                    </div>

                    <div class="form-group">
                        <label for="achat_don_id">Don en Argent</label>
                        <select class="custom-select" id="achat_don_id" name="id_don" required>
                            <option value="">Sélectionner un don</option>
                            <?php foreach ($donsArgent as $don): ?>
                                <option value="<?= $don['id_don'] ?>" data-reste="<?= $don['reste_argent'] ?>">
                                    <?= htmlspecialchars($don['libelle_don']) ?> (<?= number_format($don['reste_argent'], 2) ?> Ar)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="achat_quantite">Quantité à acheter</label>
                        <input type="number" class="form-control" id="achat_quantite" name="quantite" min="1" required>
                        <small class="form-text text-muted">Maximum: <span id="achat_max_quantite"></span></small>
                    </div>

                    <div id="simulationResult" class="alert alert-info" style="display:none;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-custom" id="btnSimuler">🔍 Simuler</button>
                <button type="button" class="btn" id="btnValider" style="background-color: #28a745; color: #fff; border: 2px solid #28a745;" disabled>✅ Valider l'Achat</button>
            </div>
        </div>
    </div>
</div>

<script>
    var simulationReussie = false;

    // Remplir les champs du modal d'achat à l'ouverture
    $('#modalAchat').on('show.bs.modal', function(e) {
        var btn = $(e.relatedTarget);
        if (btn.hasClass('btn-acheter')) {
            $('#achat_besoin_id').val(btn.data('besoin-id'));
            $('#achat_besoin_libelle').val(btn.data('besoin-libelle'));
            $('#achat_max_quantite').text(btn.data('besoin-reste'));
            $('#achat_quantite').attr('max', btn.data('besoin-reste'));
            $('#achat_quantite').val(1);
            $('#simulationResult').hide();
            $('#btnValider').prop('disabled', true);
            simulationReussie = false;
        }
    });

    // Simuler l'achat
    $('#btnSimuler').on('click', function() {
        var idDon = $('#achat_don_id').val();
        var idBesoin = $('#achat_besoin_id').val();
        var quantite = $('#achat_quantite').val();

        if (!idDon || !quantite) {
            alert('Veuillez remplir tous les champs');
            return;
        }

        $.ajax({
            url: '/api/achat/simuler',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ id_don: idDon, id_besoin: idBesoin, quantite: quantite }),
            success: function(data) {
                var resultDiv = $('#simulationResult');
                if (data.success) {
                    simulationReussie = true;
                    $('#btnValider').prop('disabled', false);
                    resultDiv.removeClass('alert-danger alert-info').addClass('alert-success');
                    resultDiv.html(
                        '<h6> Simulation Réussie</h6>' +
                        '<p><strong>Besoin:</strong> ' + data.simulation.besoin + ' (' + data.simulation.ville + ')</p>' +
                        '<p><strong>Quantité:</strong> ' + data.simulation.quantite + '</p>' +
                        '<p><strong>Montant de base:</strong> ' + data.simulation.montant_base + ' Ar</p>' +
                        '<p><strong>Frais (' + data.simulation.pourcentage_frais + '%):</strong> ' + data.simulation.frais + ' Ar</p>' +
                        '<p><strong>Montant TOTAL:</strong> ' + data.simulation.montant_total + ' Ar</p>' +
                        '<p class="mb-0"><small>Restant du don après: ' + data.simulation.montant_restant_don_apres + ' Ar</small></p>'
                    );
                } else {
                    simulationReussie = false;
                    $('#btnValider').prop('disabled', true);
                    resultDiv.removeClass('alert-success alert-info').addClass('alert-danger');
                    resultDiv.html('<strong> Erreur:</strong> ' + data.error);
                }
                resultDiv.show();
            },
            error: function(xhr) {
                alert('Erreur lors de la simulation');
            }
        });
    });

    // Valider l'achat
    $('#btnValider').on('click', function() {
        if (!simulationReussie) {
            alert("Veuillez d'abord simuler l'achat");
            return;
        }
        if (!confirm("Confirmer l'achat ?")) return;

        var idDon = $('#achat_don_id').val();
        var idBesoin = $('#achat_besoin_id').val();
        var quantite = $('#achat_quantite').val();

        $.ajax({
            url: '/api/achat/valider',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ id_don: idDon, id_besoin: idBesoin, quantite: quantite }),
            success: function(data) {
                if (data.success) {
                    alert('succees ' + data.message);
                    window.location.reload();
                } else {
                    alert('Erreur: ' + data.error);
                }
            },
            error: function(xhr) {
                alert("Erreur lors de la validation");
            }
        });
    });
</script>
