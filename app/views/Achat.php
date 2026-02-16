<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="section-title position-relative text-uppercase mb-4">
                <span class="bg-secondary pr-3">Achats de besoins</span>
            </h2>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Filtre par ville + Frais d'achat -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="bg-light p-3">
                        <label for="ville" class="font-weight-bold">Filtrer par ville :</label>
                        <select class="form-control" id="ville" onchange="window.location='/achats?id_ville='+this.value">
                            <option value="">Toutes les villes</option>
                            <?php foreach ($villes as $ville): ?>
                                <option value="<?= $ville['id_ville'] ?>" <?= (isset($id_ville) && $id_ville == $ville['id_ville']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ville['nom_ville']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-light p-3">
                        <form action="/achats/frais" method="POST" class="form-inline">
                            <label for="frais" class="font-weight-bold mr-2">Frais d'achat :</label>
                            <input type="number" step="0.01" min="0" max="100" name="frais" id="frais"
                                   value="<?= htmlspecialchars($frais ?? 10) ?>" class="form-control mr-1" style="width:80px;">
                            <span class="mr-2">%</span>
                            <button type="submit" class="btn btn-secondary btn-sm">Modifier</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tableau des besoins restants + Formulaire d'achat -->
            <form action="/achats" method="POST">
                <div class="bg-light p-4 mb-4">
                    <h5 class="mb-3">Besoins restants (nature & matériaux)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Besoin</th>
                                    <th>Type</th>
                                    <th>Qté restante</th>
                                    <th>P.U (Ar)</th>
                                    <th>Ville</th>
                                    <th>Achat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($besoins_restants)): ?>
                                    <tr><td colspan="6" class="text-center">Tous les besoins sont couverts !</td></tr>
                                <?php else: ?>
                                    <?php foreach ($besoins_restants as $b): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($b['libelle_besoin']) ?></td>
                                            <td>
                                                <span class="badge badge-<?= $b['type_besoin'] === 'nature' ? 'success' : 'warning' ?>">
                                                    <?= $b['type_besoin'] ?>
                                                </span>
                                            </td>
                                            <td><?= $b['quantite_restante'] ?></td>
                                            <td><?= number_format($b['prix_unitaire'], 2) ?></td>
                                            <td><?= htmlspecialchars($b['nom_ville']) ?></td>
                                            <td class="text-center">
                                                <input type="radio" name="id_besoin" value="<?= $b['id_besoin'] ?>"
                                                       data-prix="<?= $b['prix_unitaire'] ?>"
                                                       data-restant="<?= $b['quantite_restante'] ?>"
                                                       onchange="calculerCout()" required>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Quantité + Calcul -->
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label for="quantite" class="font-weight-bold">Quantité à acheter :</label>
                            <input type="number" class="form-control" id="quantite" name="quantite" min="1" required oninput="calculerCout()">
                        </div>
                        <div class="col-md-8 d-flex align-items-end">
                            <div id="calcul-cout" class="alert alert-info mb-0 w-100" style="display:none;">
                                Coût : <span id="cout-base">0</span> Ar
                                + Frais <?= htmlspecialchars($frais ?? 10) ?>% : <span id="cout-frais">0</span> Ar
                                = <strong>Total : <span id="cout-total">0</span> Ar</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Solde + Bouton -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p class="mb-0"><strong>Dons argent disponibles : </strong>
                                <span class="text-primary font-weight-bold"><?= number_format($solde ?? 0, 2) ?> Ar</span>
                            </p>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-shopping-cart"></i> Acheter
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
function calculerCout() {
    var radios = document.getElementsByName('id_besoin');
    var selected = null;
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) { selected = radios[i]; break; }
    }
    var qte = document.getElementById('quantite');
    var div = document.getElementById('calcul-cout');

    if (!selected || !qte.value) { div.style.display = 'none'; return; }

    var prix = parseFloat(selected.getAttribute('data-prix'));
    var restant = parseInt(selected.getAttribute('data-restant'));
    var quantite = parseInt(qte.value);
    var frais = <?= json_encode((float)($frais ?? 10)) ?>;

    if (quantite > restant) { qte.value = restant; quantite = restant; }

    var base = quantite * prix;
    var montantFrais = base * (frais / 100);
    var total = base + montantFrais;

    document.getElementById('cout-base').textContent = base.toFixed(2);
    document.getElementById('cout-frais').textContent = montantFrais.toFixed(2);
    document.getElementById('cout-total').textContent = total.toFixed(2);
    div.style.display = 'block';
}
</script>
