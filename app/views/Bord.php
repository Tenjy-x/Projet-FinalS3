<div class="container" style="padding-top: 120px; padding-bottom: 50px;">
    <h2 class="text-center mb-4">Tableau de Bord - Suivi des Villes et Besoins</h2>

    <div class="text-end mb-3">
        <a href="/dispatch" class="btn btn-primary">Lancer Dispatch</a>
    </div>

    <div class="row">
        <div class="col-lg-12 table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th style="min-width: 260px;">Ville &amp; Résumé</th>
                        <th style="min-width: 520px;">Besoins</th>
                        <th style="min-width: 320px;">Dons reçus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($villes as $ville): ?>
                    <tr>
                        <td>
                            <strong>🏙️ <?= $ville['nom_ville'] ?></strong><br>
                            Total besoins: <strong></strong><br>
                            Reste: <strong>400k Ar</strong>
                        </td>
                        <td>
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Besoin</th>
                                        <th>Montant</th>
                                        <th>Reçu</th>
                                        <th>Reste</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($villesBesoins as $vB):?>
                                    <tr>
                                        <td><?php echo $vB['libelle_besoin']?></td>
                                        <td><?php echo $vB['montant_total']?></td>
                                        <td>60kg (120k)</td>
                                        <td>40kg (80k)</td>
                                        <td>⚠️ Partiel</td>
                                    </tr>
                                    <?php endforeach?>
                                    <!-- <tr>
                                        <td>Huile 50L @5000</td>
                                        <td>250k Ar</td>
                                        <td>50L (250k)</td>
                                        <td>0</td>
                                        <td>✅ Complet</td>
                                    </tr>
                                    <tr>
                                        <td>Tôles 20 @15000</td>
                                        <td>300k Ar</td>
                                        <td>0</td>
                                        <td>20 (300k)</td>
                                        <td>❌ Urgent</td>
                                    </tr>
                                    <tr>
                                        <td>Argent</td>
                                        <td>500k Ar</td>
                                        <td>200k</td>
                                        <td>300k</td>
                                        <td>⚠️ Partiel</td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="table table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <td>16/02 - Croix Rouge</td>
                                        <td>Riz 50kg (100k Ar)</td>
                                    </tr>
                                    <tr>
                                        <td>16/02 - UNICEF</td>
                                        <td>Argent 200k Ar</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <?php endforeach ?>
                    <!-- <tr>
                        <td>
                            <strong>🏙️ TOLIARA</strong><br>
                            Total besoins: <strong>800k Ar</strong><br>
                            Reçu: <strong>350k Ar</strong><br>
                            Reste: <strong>450k Ar</strong>
                        </td>
                        <td>
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Besoin</th>
                                        <th>Montant</th>
                                        <th>Reçu</th>
                                        <th>Reste</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Tôles 15 @15000</td>
                                        <td>225k Ar</td>
                                        <td>10 (150k)</td>
                                        <td>5 (75k)</td>
                                        <td>⚠️ Partiel</td>
                                    </tr>
                                    <tr>
                                        <td>Clous 3000 @0.1</td>
                                        <td>300k Ar</td>
                                        <td>3000 (300k)</td>
                                        <td>0</td>
                                        <td>✅ Complet</td>
                                    </tr>
                                    <tr>
                                        <td>Ciment 10T @20k</td>
                                        <td>200k Ar</td>
                                        <td>0</td>
                                        <td>10T (200k)</td>
                                        <td>❌ Urgent</td>
                                    </tr>
                                    <tr>
                                        <td>Argent</td>
                                        <td>75k Ar</td>
                                        <td>50k</td>
                                        <td>25k</td>
                                        <td>⚠️ Partiel</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="table table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <td>15/02 - Entreprise Bâtiment</td>
                                        <td>Tôles 10 (150k Ar)</td>
                                    </tr>
                                    <tr>
                                        <td>14/02 - Collecte locale</td>
                                        <td>Clous 3000 (300k Ar)</td>
                                    </tr>
                                    <tr>
                                        <td>14/02 - Donateur privé</td>
                                        <td>Argent 50k Ar</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>🏙️ ANTSIRABE</strong><br>
                            Total besoins: <strong>500k Ar</strong><br>
                            Reçu: <strong>500k Ar</strong><br>
                            Reste: <strong>0 Ar</strong>
                        </td>
                        <td>
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Besoin</th>
                                        <th>Montant</th>
                                        <th>Reçu</th>
                                        <th>Reste</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Argent pour école</td>
                                        <td>500k Ar</td>
                                        <td>500k</td>
                                        <td>0</td>
                                        <td>✅ Complet</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="table table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <td>11/02 - Collecte nationale</td>
                                        <td>Argent 500k Ar</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>🏙️ MAHAJANGA</strong><br>
                            Total besoins: <strong>350k Ar</strong><br>
                            Reçu: <strong>0 Ar</strong><br>
                            Reste: <strong>350k Ar</strong>
                        </td>
                        <td>
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Besoin</th>
                                        <th>Montant</th>
                                        <th>Reçu</th>
                                        <th>Reste</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Médicaments 200 @1750</td>
                                        <td>350k Ar</td>
                                        <td>0</td>
                                        <td>200 (350k)</td>
                                        <td>❌ Urgent</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="table table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <td>Aucun don reçu pour le moment</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody> -->
            </table>
        </div>
    </div>
</div>