<!-- Filtre par ville -->
<div class="container-fluid" style="margin-top: 170px;">
    <div class="row mb-4">
            <div class="col-md-4">
                <form method="GET" action="<?= BASE_URL ?>achats">
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
                        <button type="submit" class="btn btn-custom">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistiques -->
        <?php if (!empty($achats)): ?>
            <?php
                $totalMontant = array_sum(array_column($achats, 'montant_total'));
                $totalQuantite = array_sum(array_column($achats, 'quantite'));
            ?>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card" style="border-left: 4px solid #FDBE33;">
                        <div class="card-body">
                            <h6 class="text-muted">Montant Total des Achats</h6>
                            <h3 style="color: #FDBE33;" class="mb-0"><?= number_format($totalMontant, 2) ?> Ar</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card" style="border-left: 4px solid #4a4c70;">
                        <div class="card-body">
                            <h6 class="text-muted">Nombre d'Achats</h6>
                            <h3 style="color: #4a4c70;" class="mb-0"><?= count($achats) ?> achat(s)</h3>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Liste des achats -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="background-color: #20212B; color: #ffffff;">
                        <h5 class="mb-0">Liste des Achats</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($achats)): ?>
                            <div class="alert alert-info">
                                Aucun achat n'a encore été effectué
                                <?php if (isset($id_ville_selectionnee) && $id_ville_selectionnee): ?>
                                    pour cette ville
                                <?php endif; ?>.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead style="background-color: #4a4c70; color: #ffffff;">
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Ville</th>
                                            <th>Besoin</th>
                                            <th>Type</th>
                                            <th>Quantité</th>
                                            <th>Prix Unit.</th>
                                            <th>Montant</th>
                                            <th>Frais</th>
                                            <th>Total</th>
                                            <th>Don Utilisé</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($achats as $index => $achat): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td>
                                                    <small><?= date('d/m/Y H:i', strtotime($achat['date_achat'])) ?></small>
                                                </td>
                                                <td><?= htmlspecialchars($achat['nom_ville']) ?></td>
                                                <td><?= htmlspecialchars($achat['nom_produit']) ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $achat['nom_type'] ?>">
                                                        <?= ucfirst($achat['nom_type']) ?>
                                                    </span>
                                                </td>
                                                <td><?= $achat['quantite'] ?></td>
                                                <td><?= number_format($achat['prix_unitaire'], 2) ?> Ar</td>
                                                <td><?= number_format($achat['montant'], 2) ?> Ar</td>
                                                <td class="text-danger"><?= number_format($achat['frais'], 2) ?> Ar</td>
                                                <td><strong><?= number_format($achat['montant_total'], 2) ?> Ar</strong></td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?= htmlspecialchars($achat['libelle_don']) ?>
                                                    </small>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-primary">
                                            <th colspan="5" class="text-end">TOTAL</th>
                                            <th><?= $totalQuantite ?></th>
                                            <th colspan="3"></th>
                                            <th><strong><?= number_format($totalMontant, 2) ?> Ar</strong></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
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
            <a href="<?=BASE_URL?>besoins-restants" class="btn btn-custom btn-lg">
                Effectuer un Nouvel Achat
            </a>
            <a href="<?=BASE_URL?>recap" class="btn btn-custom btn-lg" style="margin-left: 10px;">
                Voir le Récapitulatif
            </a>
        </div>
    </div>
</div>
