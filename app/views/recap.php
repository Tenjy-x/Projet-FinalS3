<div class="container-fluid" style="margin-top: 170px;">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #20212B; color: #ffffff;">
                        <h4 class="mb-0">Récapitulatif Global des Besoins</h4>
                        <button class="btn btn-sm" style="border: 1px solid #FDBE33; color: #FDBE33;" id="btnActualiser">
                            Actualiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="recapContent">
            <div class="row mb-4">
                <!-- Montant Total -->
                <div class="col-md-4">
                    <div class="card" style="border-left: 4px solid #4a4c70;">
                        <div class="card-body">
                            <h6 class="text-muted">Montant Total des Besoins</h6>
                            <h2 class="mb-0" style="color: #4a4c70;" id="montantTotal">
                                <?= number_format($recap['montant_total'] ?? 0, 2) ?> Ar
                            </h2>
                        </div>
                    </div>
                </div>

                <!-- Montant Satisfait -->
                <div class="col-md-4">
                    <div class="card" style="border-left: 4px solid #28a745;">
                        <div class="card-body">
                            <h6 class="text-muted">Montant Satisfait</h6>
                            <h2 class="mb-0 text-success" id="montantSatisfait">
                                <?= number_format($recap['montant_satisfait'] ?? 0, 2) ?> Ar
                            </h2>
                        </div>
                    </div>
                </div>

                <!-- Montant Restant -->
                <div class="col-md-4">
                    <div class="card" style="border-left: 4px solid #dc3545;">
                        <div class="card-body">
                            <h6 class="text-muted">Montant Restant</h6>
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
                        <div class="card-header" style="background-color: #4a4c70; color: #ffffff;">
                            <h5 class="mb-0">Détails</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Catégorie</th>
                                        <th class="text-right">Montant (Ar)</th>
                                        <th class="text-right">Pourcentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-primary">
                                        <td><strong>Total des Besoins</strong></td>
                                        <td class="text-right"><strong id="detailTotal"><?= number_format($recap['montant_total'] ?? 0, 2) ?></strong></td>
                                        <td class="text-right">100%</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>Satisfait</td>
                                        <td class="text-right text-success" id="detailSatisfait"><?= number_format($recap['montant_satisfait'] ?? 0, 2) ?></td>
                                        <td class="text-right text-success" id="detailPourcentageSatisfait"><?= round($pourcentage, 2) ?>%</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>Restant</td>
                                        <td class="text-right text-danger" id="detailRestant"><?= number_format($recap['montant_restant'] ?? 0, 2) ?></td>
                                        <td class="text-right text-danger" id="detailPourcentageRestant"><?= round(100 - $pourcentage, 2) ?>%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loader pour actualisation -->
        <div id="recapLoader" style="display:none;" class="text-center py-5">
            <div class="spinner-border" style="color: #FDBE33;" role="status"></div>
            <p class="mt-2 text-muted">Mise à jour en cours...</p>
        </div>

        <!-- Boutons de navigation -->
        <div class="row mt-4 mb-5">
            <div class="col-md-12 text-center">
                <a href="<?=BASE_URL?>besoins-restants" class="btn btn-custom btn-lg">Effectuer un Achat</a>
                <a href="<?=BASE_URL?>achats" class="btn btn-custom btn-lg" style="margin-left: 10px;">Voir les Achats</a>
                <a href="<?=BASE_URL?>bord" class="btn btn-custom btn-lg" style="margin-left: 10px;">Tableau de Bord</a>
            </div>
        </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function actualiserRecap() {
        var recapLoader = document.getElementById('recapLoader');
        var content = document.getElementById('recapContent');
        var btn = document.getElementById('btnActualiser');

        btn.disabled = true;
        btn.innerHTML = 'Chargement...';
        recapLoader.style.display = 'block';
        content.style.opacity = '0.5';

        $.ajax({
            url: '<?= BASE_URL ?>api/recap',
            method: 'GET',
            success: function(data) {
                if (data.success) {
                    var recap = data.recap;

                    $('#montantTotal').text(formatNumber(recap.montant_total) + ' Ar');
                    $('#montantSatisfait').text(formatNumber(recap.montant_satisfait) + ' Ar');
                    $('#montantRestant').text(formatNumber(recap.montant_restant) + ' Ar');

                    var pourcentage = recap.pourcentage_satisfait;
                    $('#progressBar').css('width', pourcentage + '%');
                    $('#pourcentageText').text(pourcentage + '%');

                    $('#detailTotal').text(formatNumber(recap.montant_total));
                    $('#detailSatisfait').text(formatNumber(recap.montant_satisfait));
                    $('#detailRestant').text(formatNumber(recap.montant_restant));
                    $('#detailPourcentageSatisfait').text(pourcentage + '%');
                    $('#detailPourcentageRestant').text((100 - pourcentage).toFixed(2) + '%');

                    content.style.opacity = '1';
                } else {
                    alert('Erreur: ' + data.error);
                }
            },
            error: function() {
                alert('Erreur de connexion');
            },
            complete: function() {
                recapLoader.style.display = 'none';
                content.style.opacity = '1';
                btn.disabled = false;
                btn.innerHTML = ' Actualiser';
            }
        });
    }

    function formatNumber(num) {
        return parseFloat(num).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }

    document.getElementById('btnActualiser').addEventListener('click', actualiserRecap);
});
</script>

