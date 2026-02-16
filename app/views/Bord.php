<div class="container-fluid" style="padding-top: 100px; padding-bottom: 50px;">

    <!-- TITRE / EN-TÊTE -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="mb-1">Tableau de bord BNGRC</h2>
            <p class="text-muted mb-3">Vue d'ensemble de la situation humanitaire dans toutes les villes</p>
            <a href="/dispatch" class="btn btn-primary">Lancer le dispatch automatique</a>
        </div>
    </div>

    <!-- COMPTEURS GLOBAUX -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-primary">
                <div class="card-body">
                    <h2 class="text-primary mb-0"><?= htmlspecialchars($stats['nombre_villes']) ?></h2>
                    <p class="mb-0">Villes concernées</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-danger">
                <div class="card-body">
                    <h2 class="text-danger mb-0"><?= htmlspecialchars($stats['nombre_besoins']) ?></h2>
                    <p class="mb-0">Besoins enregistrés</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-success">
                <div class="card-body">
                    <h2 class="text-success mb-0"><?= htmlspecialchars($stats['nombre_dons']) ?></h2>
                    <p class="mb-0">Dons reçus</p>
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
    <!-- TAUX DE SATISFACTION -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Taux de satisfaction global</h5>
                    <?php 
                    $taux = floatval($stats['taux_satisfaction']);
                    $color = $taux < 30 ? 'danger' : ($taux < 70 ? 'warning' : 'success');
                    ?>
                    <div class="progress" style="height: 30px;">
                        <div class="progress-bar bg-<?= $color ?>" role="progressbar" 
                             style="width: <?= $taux ?>%;" aria-valuenow="<?= $taux ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= number_format($taux, 1) ?>%
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <small class="text-muted">
                                Reçu: <strong><?= number_format($stats['montant_total_recu'], 0, ',', ' ') ?> Ar</strong>
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                Total besoins: <strong><?= number_format($stats['montant_total_besoins'], 0, ',', ' ') ?> Ar</strong>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BOUTON DISPATCH -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <a href="/dispatch" class="btn btn-warning btn-lg shadow">
                Lancer le dispatch automatique
            </a>
        </div>
    </div>

    <!-- SECTION ALERTES -->
    <?php if (!empty($besoinsUrgents) || !empty($donsEnAttente)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Alertes</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Besoins urgents -->
                        <?php if (!empty($besoinsUrgents)): ?>
                        <div class="col-md-6">
                            <h6 class="text-danger">Besoins non satisfaits depuis plus de trois jours</h6>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($besoinsUrgents as $urgent): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <strong><?= htmlspecialchars($urgent['nom_ville']) ?></strong> - 
                                        <?= htmlspecialchars($urgent['libelle_besoin']) ?>
                                        <small class="text-muted">(<?= htmlspecialchars($urgent['type_besoin']) ?>)</small>
                                    </span>
                                    <span class="badge bg-danger"><?= $urgent['jours_attente'] ?> jours</span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Dons en attente -->
                        <?php if (!empty($donsEnAttente)): ?>
                        <div class="col-md-6">
                            <h6 class="text-warning">Dons en attente d'attribution</h6>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($donsEnAttente as $don): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <?= htmlspecialchars($don['libelle_don']) ?>
                                        <small class="text-muted">(<?= htmlspecialchars($don['type_don']) ?>)</small>
                                    </span>
                                    <span class="badge bg-warning text-dark">
                                        <?= number_format($don['quantite_restante'], 0, ',', ' ') ?> restants
                                    </span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- DÉTAILS PAR VILLE -->
    <h4 class="mb-3">Situation par ville</h4>
    
    <?php foreach ($villes as $ville): ?>
    <?php
    // Calculer les totaux pour cette ville
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
    <div class="card mb-4 shadow-sm" style="border-left: 5px solid #0d6efd;">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= htmlspecialchars($ville['nom_ville']) ?></h5>
                <div>
                    <?php if ($nb_urgent > 0): ?>
                        <span class="badge bg-danger"><?= $nb_urgent ?> urgents</span>
                    <?php endif; ?>
                    <?php if ($nb_partiel > 0): ?>
                        <span class="badge bg-warning text-dark"><?= $nb_partiel ?> partiels</span>
                    <?php endif; ?>
                    <?php if ($nb_complet > 0): ?>
                        <span class="badge bg-success"><?= $nb_complet ?> satisfaits</span>
                    <?php endif; ?>
                </div>
            </div>
            <small>
                Total: <strong><?= number_format($montant_total, 0, ',', ' ') ?> Ar</strong> | 
                Reçu: <strong><?= number_format($montant_recu, 0, ',', ' ') ?> Ar</strong> | 
                Reste: <strong><?= number_format($montant_reste, 0, ',', ' ') ?> Ar</strong>
            </small>
        </div>
        <div class="card-body">
            <!-- Tableau des besoins -->
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
                        <?php foreach ($ville['besoins'] as $besoin): ?>
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
                        $s = $statut_info[$besoin['statut']];
                        ?>
                        <tr class="<?= $besoin['statut'] === 'urgent' ? 'table-danger' : '' ?>">
                            <td><strong><?= htmlspecialchars($besoin['libelle_besoin']) ?></strong></td>
                            <td>
                                <span class="badge bg-<?= $badge_type[$besoin['type_besoin']] ?>">
                                    <?= htmlspecialchars(strtoupper($besoin['type_besoin'])) ?>
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

            <!-- Dons attribués à cette ville -->
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
            <div class="mt-3">
                <h6 class="text-muted">Dons attribués à cette ville</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-secondary">
                            <tr>
                                <th>Date</th>
                                <th>Pour besoin</th>
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
                                        <span class="badge bg-<?= $badge_type[$attr['type_don']] ?> badge-sm">
                                            <?= htmlspecialchars(strtoupper($attr['type_don'])) ?>
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
            </div>
            <?php else: ?>
            <div class="alert alert-warning mt-3 mb-0">
                Aucun don n'a encore été attribué à cette ville. 
                <a href="/dispatch" class="alert-link">Lancer le dispatch</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- LÉGENDE -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">Légende des statuts</h6>
                    <span class="badge bg-danger me-2">Urgent</span> Aucun don reçu
                    <span class="badge bg-warning text-dark mx-2">Partiel</span> Besoin partiellement satisfait
                    <span class="badge bg-success mx-2">Satisfait</span> Besoin entièrement couvert
                </div>
            </div>
        </div>
    </div>
</div>
