<div class="container-fluid" style="margin-top: 170px;">
    <div class="row">
        <div class="col-lg-7 mx-auto">

            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            <?php endif; ?>
            
            <div class="card-header" style="background-color: #20212B; color: #ffffff;">
                    <h4 class="mb-0">Déclarer un Besoin</h4>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>besoin" method="POST">
                        <div class="form-group">
                            <label for='nom_produit'>Produit</label>
                            <input type="text" class="form-control" id="nom_produit" name="nom_produit" list="liste_produits_besoin" placeholder="Tapez le nom du produit (ex: Riz, Tôles...)" autocomplete="off" required>
                            <datalist id="liste_produits_besoin">
                                <?php foreach ($produits as $produit): ?>
                                    <option value="<?= htmlspecialchars($produit['nom_produit']) ?>">
                                <?php endforeach; ?>
                            </datalist>
                            <small class="form-text text-muted">Sélectionnez un produit existant ou tapez un nouveau nom pour le créer automatiquement.</small>
                        </div>
                        <div class="form-group" id="type_group_besoin">
                            <label for='id_type'>Type</label>
                            <select class="custom-select" id="id_type" name="id_type" required>
                                <option value="">Sélectionnez un type</option>
                                <?php foreach ($types as $type): ?>
                                    <option value="<?= $type['id_type'] ?>"><?= htmlspecialchars(ucfirst($type['nom_type'])) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">Le type est rempli automatiquement pour les produits existants.</small>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for='quantite'>Quantité</label>
                                <input type="number" class="form-control" id="quantite" name="quantite" placeholder="Quantité" min="1" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for='prix_unitaire'>Prix unitaire (Ar)</label>
                                <input type="number" step="0.01" class="form-control" id="prix_unitaire" name="prix_unitaire" placeholder="Prix unitaire" min="0" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for='id_ville'>Ville</label>
                            <select class="custom-select" id="id_ville" name="id_ville" required>
                                <option value="">Sélectionnez une ville</option>
                                <?php foreach ($villes as $ville): ?>
                                    <option value="<?= htmlspecialchars($ville['id_ville']) ?>"><?= htmlspecialchars($ville['nom_ville']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-custom btn-lg">
                                <i class="fas fa-paper-plane"></i> Soumettre le Besoin
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
            (function() {
                const produits = <?= json_encode($produits) ?>;
                const inputProduit = document.getElementById('nom_produit');
                const selectType = document.getElementById('id_type');

                inputProduit.addEventListener('input', function() {
                    const val = this.value.trim().toLowerCase();
                    const match = produits.find(p => p.nom_produit.toLowerCase() === val);
                    if (match) {
                        selectType.value = match.id_type;
                        selectType.disabled = true;
                    } else {
                        selectType.disabled = false;
                    }
                });

                inputProduit.closest('form').addEventListener('submit', function() {
                    selectType.disabled = false;
                });
            })();
            </script>

        </div>
    </div>
</div>