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

            <div class="card mb-5">
                <div class="card-header" style="background-color: #20212B; color: #ffffff;">
                    <h4 class="mb-0">Déclarer un Besoin</h4>
                </div>
                <div class="card-body p-4">
                    <form action="/besoin" method="POST">
                        <div class="form-group">
                            <label for='libelle_besoin'>Description du besoin</label>
                            <input type="text" class="form-control" id="libelle_besoin" name="libelle_besoin" placeholder="Entrez la description du besoin" required>
                        </div>
                        <div class="form-group">
                            <label for='id_type'>Type de besoin</label>
                            <select class="custom-select" id="id_type" name="id_type" required>
                                <option value="">Sélectionnez un type</option>
                                <?php foreach ($types as $type): ?>
                                    <option value="<?= htmlspecialchars($type['id_type']) ?>"><?= htmlspecialchars($type['nom_type']) ?></option>
                                <?php endforeach; ?>
                            </select>
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

        </div>
    </div>
</div>