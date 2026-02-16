<div class="container-fluid py-5">
    <div class="row px-xl-5">
        <div class="col-lg-8 mx-auto">
            <h2 class="section-title position-relative text-uppercase mb-4">
                <span class="bg-secondary pr-3">Doner votre dons</span>
            </h2>
            <div class="bg-light p-4 mb-5">
                <form action="/dons" method="POST">
                    <div class="form-group">
                        <label for='description'>Description du don</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Entrez la description du don" required>
                    </div>
                    <div class="form-group">
                        <label for='type'>Type de don</label>
                        <select class="form-control" id="type" name="type" required>
                            <?php foreach ($type as $types): ?>
                                <option value="<?= $types['id'] ?>"><?= $types['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for='quantites'>Quantités</label>
                        <input type="number" class="form-control" id="quantites" name="quantites" placeholder="Entrez la quantité du don" required>
                    </div>
                    <div class="form-group">
                        <label for='date'>Date du don</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                </form>
            </div>
        </div>
    </div>
</div>