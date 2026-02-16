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
            <div class="bg-light p-4 mb-5">
                <form action="<?= BASE_URL ?>dons" method="POST">
                    <div class="form-group">
                        <label for='description'>Description du don</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Entrez la description du don" required>
                    </div>
                    <div class="form-group">
                        <label for='nom_produit'>Produit</label>
                        <input type="text" class="form-control" id="nom_produit" name="nom_produit" list="liste_produits" placeholder="Tapez le nom du produit (ex: Riz, Huile...)" autocomplete="off" required>
                        <datalist id="liste_produits">  
                            <?php foreach ($produits as $produit): ?>
                                <option value="<?= htmlspecialchars($produit['nom_produit']) ?>" data-type="<?= htmlspecialchars($produit['nom_type']) ?>">
                            <?php endforeach; ?>
                        </datalist>
                        <small class="form-text text-muted">Sélectionnez un produit existant ou tapez un nouveau nom pour le créer automatiquement.</small>
                    </div>
                    <div class="form-group" id="type_group">
                        <label for='id_type'>Type</label>
                        <select class="form-control" id="id_type" name="id_type" required>
                            <option value="">Sélectionnez un type</option>
                            <?php foreach ($types as $type): ?>
                                <option value="<?= $type['id_type'] ?>"><?= htmlspecialchars(ucfirst($type['nom_type'])) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">Le type est rempli automatiquement pour les produits existants.</small>
                    </div>
                    <div class="form-group">
                        <label for='quantites'>Quantités / Montant</label>
                        <input type="number" class="form-control" id="quantites" name="quantites" placeholder="Entrez la quantité du don" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                </form>
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

                // Avant submit, réactiver le select pour envoyer la valeur
                inputProduit.closest('form').addEventListener('submit', function() {
                    selectType.disabled = false;
                });
            })();
            </script>

        </div>
    </div>
</div>