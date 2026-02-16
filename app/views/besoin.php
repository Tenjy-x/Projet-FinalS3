<div class="container-fluid py-5">
    <div class="row px-xl-5">
        <div class="col-lg-8 mx-auto">
            <h2 class="section-title position-relative text-uppercase mb-4">
                <span class="bg-secondary pr-3">Enregistrer votre besoin</span>
            </h2>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <div class="bg-light p-4 mb-5">
                <form action="<?= BASE_URL ?>/besoin" method="POST">
                    <div class="form-group">
                        <label for='libelle_besoin'>Description du besoin</label>
                        <input type="text" class="form-control" id="libelle_besoin" name="libelle_besoin" placeholder="Entrez la description du besoin" required>
                    </div>
                    <div class="form-group">
                        <label for='type_besoin'>Type de besoin</label>
                        <select class="form-control" id="type_besoin" name="type_besoin" required>
                            <option value="">Sélectionnez un type</option>
                            <?php foreach ($types as $type): ?>
                                <option value="<?= htmlspecialchars($type['id']) ?>"><?= htmlspecialchars($type['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for='quantite'>Quantité</label>
                        <input type="number" class="form-control" id="quantite" name="quantite" placeholder="Entrez la quantité" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for='prix_unitaire'>Prix unitaire</label>
                        <input type="number" step="0.01" class="form-control" id="prix_unitaire" name="prix_unitaire" placeholder="Entrez le prix unitaire" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for='id_ville'>Ville</label>
                        <select class="form-control" id="id_ville" name="id_ville" required>
                            <option value="">Sélectionnez une ville</option>
                            <?php foreach ($villes as $ville): ?>
                                <option value="<?= htmlspecialchars($ville['id_ville']) ?>"><?= htmlspecialchars($ville['nom_ville']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                </form>
            </div>
        </div>
    </div>
</div>