<div class="container mt-4" style="padding-top: 100px; padding-bottom: 50px;">

    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="mb-1">Tableau de bord</h2>
            <p class="text-muted mb-3">Liste des villes, besoins et dons attribués</p>
            <a href="<?= BASE_URL ?>dispatch" class="btn btn-primary">Lancer le dispatch</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-primary">
                <div class="card-body">
                    <h2 class="text-primary mb-0"><?= htmlspecialchars($stats['nombre_villes']) ?></h2>
                    <p class="mb-0">Villes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-danger">
                <div class="card-body">
                    <h2 class="text-danger mb-0"><?= htmlspecialchars($stats['nombre_besoins']) ?></h2>
                    <p class="mb-0">Besoins</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-success">
                <div class="card-body">
                    <h2 class="text-success mb-0"><?= htmlspecialchars($stats['nombre_dons']) ?></h2>
                    <p class="mb-0">Dons</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-info">
                <div class="card-body">
                    <h2 class="text-info mb-0"><?= htmlspecialchars($stats['nombre_attributions']) ?></h2>
                    <p class="mb-0">Attributions</p>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-light border mb-4">
        <strong>Légende :</strong>
        <span class="badge bg-danger ms-2">Urgent</span>
        <span class="badge bg-warning text-dark ms-2">Partiel</span>
        <span class="badge bg-success ms-2">Satisfait</span>
    </div>

    <h4 class="mb-3">Villes et besoins</h4>
    
    <?php foreach ($villes as $ville): ?>
    <?php
    $montant_total = 0;
    $montant_recu = 0;
    $nb_urgent = 0;
    $nb_partiel = 0;
    $nb_complet = 0;
    foreach ($ville['besoins'] as $besoin) {
        $montant_total += $besoin['montant_besoin'];
        $montant_recu += $besoin['montant_recu'];
        if ($besoin['statut'] === 'urgent') $nb_urgent++;
        elseif ($besoin['statut'] === 'partiel') $nb_partiel++;
        else $nb_complet++;
    }
    $montant_reste = $montant_total - $montant_recu;
    ?>
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= htmlspecialchars($ville['nom_ville']) ?></h5>
                <small class="text-muted">
                    Total: <strong><?= number_format($montant_total, 0, ',', ' ') ?> Ar</strong> | 
                    Reçu: <strong><?= number_format($montant_recu, 0, ',', ' ') ?> Ar</strong> | 
                    Reste: <strong><?= number_format($montant_reste, 0, ',', ' ') ?> Ar</strong>
                </small>
            </div>
        </div>
        <div class="card-body">
            <h6 class="mb-2">Besoins</h6>
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Produit</th>
                            <th>Type</th>
                            <th class="text-end">Qté demandée</th>
                            <th class="text-end">Prix unit.</th>
                            <th class="text-end">Montant</th>
                            <th class="text-end">Reçu</th>
                            <th class="text-end">Reste</th>
                            <th class="text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $badge_type = [
                            'nature' => 'success',
                            'materiaux' => 'primary',
                            'argent' => 'warning'
                        ];
                        $statut_info = [
                            'urgent' => ['label' => 'Urgent', 'class' => 'danger'],
                            'partiel' => ['label' => 'Partiel', 'class' => 'warning'],
                            'complet' => ['label' => 'Satisfait', 'class' => 'success']
                        ];
                        ?>
                        <?php foreach ($ville['besoins'] as $besoin): ?>
                        <?php $s = $statut_info[$besoin['statut']]; ?>
                        <tr class="<?= $besoin['statut'] === 'urgent' ? 'table-danger' : '' ?>">
                            <td><strong><?= htmlspecialchars($besoin['libelle_besoin']) ?></strong></td>
                            <td>
                                <span class="badge bg-<?= $badge_type[$besoin['nom_type']] ?>">
                                    <?= htmlspecialchars(strtoupper($besoin['nom_type'])) ?>
                                </span>
                            </td>
                            <td class="text-end"><?= number_format($besoin['quantite_besoin'], 0, ',', ' ') ?></td>
                            <td class="text-end"><?= number_format($besoin['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                            <td class="text-end"><strong><?= number_format($besoin['montant_besoin'], 0, ',', ' ') ?> Ar</strong></td>
                            <td class="text-end text-success">
                                <?= number_format($besoin['quantite_recue'], 0, ',', ' ') ?>
                                <small>(<?= number_format($besoin['montant_recu'], 0, ',', ' ') ?> Ar)</small>
                            </td>
                            <td class="text-end text-danger">
                                <?= number_format($besoin['quantite_reste'], 0, ',', ' ') ?>
                                <small>(<?= number_format($besoin['montant_reste'], 0, ',', ' ') ?> Ar)</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-<?= $s['class'] ?>">
                                    <?= $s['label'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <h6 class="mt-4 mb-2">Dons attribués</h6>
            <?php
            $has_attributions = false;
            foreach ($ville['besoins'] as $besoin) {
                if (!empty($besoin['attributions'])) {
                    $has_attributions = true;
                    break;
                }
            }
            ?>
            <?php if ($has_attributions): ?>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-secondary">
                        <tr>
                            <th>Date</th>
                            <th>Besoin</th>
                            <th>Donateur / Don</th>
                            <th>Type</th>
                            <th class="text-end">Quantité</th>
                            <th class="text-end">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ville['besoins'] as $besoin): ?>
                            <?php foreach ($besoin['attributions'] as $attr): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($attr['date_attribution'])) ?></td>
                                <td><?= htmlspecialchars($besoin['libelle_besoin']) ?></td>
                                <td><?= htmlspecialchars($attr['libelle_don']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $badge_type[$attr['nom_type']] ?>">
                                        <?= htmlspecialchars(strtoupper($attr['nom_type'])) ?>
                                    </span>
                                </td>
                                <td class="text-end"><?= number_format($attr['quantite'], 0, ',', ' ') ?></td>
                                <td class="text-end"><?= number_format($attr['montant'], 0, ',', ' ') ?> Ar</td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-warning mb-0">Aucun don attribué pour cette ville.</div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
