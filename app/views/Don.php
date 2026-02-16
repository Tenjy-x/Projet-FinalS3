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
                <form action="<?= BASE_URL ?>/dons" method="POST">
                    <div class="form-group">
                        <label for='description'>Description du don</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Entrez la description du don" required>
                    </div>
                    <div class="form-group">
                        <label for='type'>Type de don</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="">Sélectionnez un type</option>
                            <?php foreach ($type as $types): ?>
                                <option value="<?= $types['id'] ?>"><?= $types['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for='quantites'>Quantités / Montant</label>
                        <input type="number" class="form-control" id="quantites" name="quantites" placeholder="Entrez la quantité du don" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                </form>
            </div>

        </div>
    </div>
</div>