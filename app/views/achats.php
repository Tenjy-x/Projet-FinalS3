<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Achats - BNGRC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-header { background-color: #007bff; color: white; }
        .badge-nature { background-color: #28a745; }
        .badge-materiaux { background-color: #ffc107; color: #000; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">BNGRC - Historique des Achats</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/">Accueil</a>
                <a class="nav-link" href="/besoins-restants">Achats</a>
                <a class="nav-link active" href="/achats">Historique</a>
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
                    <div class="card-header">
                        <h4 class="mb-0">📜 Historique des Achats Effectués</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtre par ville -->
        <div class="row mb-4">
            <div class="col-md-4">
                <form method="GET" action="/achats">
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

        <!-- Statistiques -->
        <?php if (!empty($achats)): ?>
            <?php
                $totalMontant = array_sum(array_column($achats, 'montant_total'));
                $totalQuantite = array_sum(array_column($achats, 'quantite'));
            ?>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="text-muted">💰 Montant Total des Achats</h6>
                            <h3 class="text-primary mb-0"><?= number_format($totalMontant, 2) ?> Ar</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-info">
                        <div class="card-body">
                            <h6 class="text-muted">🛒 Nombre d'Achats</h6>
                            <h3 class="text-info mb-0"><?= count($achats) ?> achat(s)</h3>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Liste des achats -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📋 Liste des Achats</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($achats)): ?>
                            <div class="alert alert-info">
                                ℹ️ Aucun achat n'a encore été effectué
                                <?php if (isset($id_ville_selectionnee) && $id_ville_selectionnee): ?>
                                    pour cette ville
                                <?php endif; ?>.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
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
                                                <td><?= htmlspecialchars($achat['libelle_besoin']) ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $achat['type_besoin'] ?>">
                                                        <?= ucfirst($achat['type_besoin']) ?>
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
        <div class="row mt-4">
            <div class="col-md-12 text-center">
                <a href="/besoins-restants" class="btn btn-primary btn-lg">
                    🛒 Effectuer un Nouvel Achat
                </a>
                <a href="/recap" class="btn btn-info btn-lg">
                    📊 Voir le Récapitulatif
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
