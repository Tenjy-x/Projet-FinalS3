<?php

namespace app\models;

class DispatchModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function insertAttribution($data) {
        $st = $this->db->prepare("INSERT INTO attribution(id_don, id_besoin, quantite) VALUES (?, ?, ?)");
        $st->execute($data);
    }

    private function getDonsWithReste()
    {
        $sql = "SELECT d.*, p.nom_produit, p.id_type, t.nom_type, d.quantite AS reste
                FROM don d
                JOIN produit p ON p.id_produit = d.id_produit
                JOIN type t ON t.id_type = p.id_type
                WHERE d.quantite > 0
                ORDER BY d.date_don ASC, d.id_don ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getBesoinsWithReste()
    {
        $sql = "SELECT b.*, p.nom_produit, p.id_type, t.nom_type, b.quantite AS reste
                FROM besoin b
                JOIN produit p ON p.id_produit = b.id_produit
                JOIN type t ON t.id_type = p.id_type
                WHERE b.quantite > 0
                ORDER BY b.date_besoin ASC, b.id_besoin ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function createAttribution($idDon, $idBesoin, $quantite)
    {
        $sql = "INSERT INTO attribution (id_don, id_besoin, quantite, date_attribution)
                VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idDon, $idBesoin, $quantite]);
    }

    private function updateDonQuantite($idDon, $quantiteUtilisee)
    {
        $sql = "UPDATE don SET quantite = quantite - ? WHERE id_don = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$quantiteUtilisee, $idDon]);
    }

    private function updateBesoinQuantite($idBesoin, $quantiteUtilisee)
    {
        $sql = "UPDATE besoin SET quantite = quantite - ? WHERE id_besoin = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$quantiteUtilisee, $idBesoin]);
    }

    private function hasColumn(string $table, string $column): bool
    {
        $sql = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$table, $column]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function dispatchDons()
    {
        $this->db->beginTransaction();
        try {
            $dons = $this->getDonsWithReste();
            $besoins = $this->getBesoinsWithReste();

            $totalAttributions = 0;
            $totalQuantite = 0;

            foreach ($dons as $don) {
                $resteDon = (int) $don['reste'];
                if ($resteDon <= 0) {
                    continue;
                }

                foreach ($besoins as $index => $besoin) {
                    if ($resteDon <= 0) {
                        break;
                    }
                    // Seulement même produit (Riz→Riz, Huile→Huile, etc.)
                    if ((int) $besoin['id_produit'] !== (int) $don['id_produit']) {
                        continue;
                    }

                    $resteBesoin = (int) $besoin['reste'];
                    if ($resteBesoin <= 0) {
                        continue;
                    }

                    $quantiteAttribuee = min($resteDon, $resteBesoin);
                    if ($quantiteAttribuee <= 0) {
                        continue;
                    }

                    $this->createAttribution($don['id_don'], $besoin['id_besoin'], $quantiteAttribuee);
                    $this->updateDonQuantite($don['id_don'], $quantiteAttribuee);
                    $this->updateBesoinQuantite($besoin['id_besoin'], $quantiteAttribuee);

                    $resteDon -= $quantiteAttribuee;
                    $besoins[$index]['reste'] = $resteBesoin - $quantiteAttribuee;

                    $totalAttributions += 1;
                    $totalQuantite += $quantiteAttribuee;
                }
            }

            $this->db->commit();
            return [
                'attributions' => $totalAttributions,
                'quantite' => $totalQuantite
            ];
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Dispatch par quantité : plus petite quantité en premier
     * Les dons et besoins sont triés par quantité croissante
     */
    public function dispatchParQuantite()
    {
        $this->db->beginTransaction();
        try {
            // Dons triés par quantité croissante (les plus petits d'abord)
            $sqlDons = "SELECT d.*, p.nom_produit, p.id_type, t.nom_type, d.quantite AS reste
                        FROM don d
                        JOIN produit p ON p.id_produit = d.id_produit
                        JOIN type t ON t.id_type = p.id_type
                        WHERE d.quantite > 0
                        ORDER BY d.quantite ASC, d.date_don ASC";
            $stmt = $this->db->prepare($sqlDons);
            $stmt->execute();
            $dons = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Besoins triés par quantité croissante (les plus petits d'abord)
            $sqlBesoins = "SELECT b.*, p.nom_produit, p.id_type, t.nom_type, b.quantite AS reste
                           FROM besoin b
                           JOIN produit p ON p.id_produit = b.id_produit
                           JOIN type t ON t.id_type = p.id_type
                           WHERE b.quantite > 0
                           ORDER BY b.quantite ASC, b.date_besoin ASC";
            $stmt = $this->db->prepare($sqlBesoins);
            $stmt->execute();
            $besoins = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $totalAttributions = 0;
            $totalQuantite = 0;

            foreach ($dons as $don) {
                $resteDon = (int) $don['reste'];
                if ($resteDon <= 0) {
                    continue;
                }

                foreach ($besoins as $index => $besoin) {
                    if ($resteDon <= 0) {
                        break;
                    }
                    // Même produit uniquement
                    if ((int) $besoin['id_produit'] !== (int) $don['id_produit']) {
                        continue;
                    }

                    $resteBesoin = (int) $besoin['reste'];
                    if ($resteBesoin <= 0) {
                        continue;
                    }

                    $quantiteAttribuee = min($resteDon, $resteBesoin);
                    if ($quantiteAttribuee <= 0) {
                        continue;
                    }

                    $this->createAttribution($don['id_don'], $besoin['id_besoin'], $quantiteAttribuee);
                    $this->updateDonQuantite($don['id_don'], $quantiteAttribuee);
                    $this->updateBesoinQuantite($besoin['id_besoin'], $quantiteAttribuee);

                    $resteDon -= $quantiteAttribuee;
                    $besoins[$index]['reste'] = $resteBesoin - $quantiteAttribuee;

                    $totalAttributions += 1;
                    $totalQuantite += $quantiteAttribuee;
                }
            }

            $this->db->commit();
            return [
                'attributions' => $totalAttributions,
                'quantite' => $totalQuantite
            ];
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Dispatch proportionnel (Largest Remainder Method)
     * Répartit un don sur tous les besoins compatibles (même type, et même ville si disponible)
     */
    public function dispatchProportional(int $donId): array
    {
        $this->db->beginTransaction();
        try {
            $hasDonVille = $this->hasColumn('don', 'id_ville');

            $sqlDon = "SELECT d.id_don, d.quantite AS reste, p.id_type";
            if ($hasDonVille) {
                $sqlDon .= ", d.id_ville";
            }
            $sqlDon .= " FROM don d
                        JOIN produit p ON p.id_produit = d.id_produit
                        WHERE d.id_don = ?
                        FOR UPDATE";
            $stmt = $this->db->prepare($sqlDon);
            $stmt->execute([$donId]);
            $don = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$don || (int) $don['reste'] <= 0) {
                $this->db->commit();
                return ['attributions' => 0, 'quantite' => 0];
            }

            $params = [(int) $don['id_type']];
            $sqlBesoins = "SELECT b.id_besoin, b.quantite AS reste, b.id_ville
                           FROM besoin b
                           JOIN produit p ON p.id_produit = b.id_produit
                           WHERE b.quantite > 0 AND p.id_type = ?";
            if ($hasDonVille && isset($don['id_ville'])) {
                $sqlBesoins .= " AND b.id_ville = ?";
                $params[] = (int) $don['id_ville'];
            }
            $sqlBesoins .= " ORDER BY b.id_besoin ASC";

            $stmt = $this->db->prepare($sqlBesoins);
            $stmt->execute($params);
            $besoins = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if (empty($besoins)) {
                $this->db->commit();
                return ['attributions' => 0, 'quantite' => 0];
            }

            $donReste = (int) $don['reste'];
            $totalBesoins = 0;
            foreach ($besoins as $b) {
                $totalBesoins += (int) $b['reste'];
            }

            if ($totalBesoins <= 0) {
                $this->db->commit();
                return ['attributions' => 0, 'quantite' => 0];
            }

            $allocations = [];
            $remainders = [];

            if ($donReste >= $totalBesoins) {
                foreach ($besoins as $b) {
                    $allocations[(int) $b['id_besoin']] = (int) $b['reste'];
                }
            } else {
                $used = 0;
                foreach ($besoins as $b) {
                    $resteBesoin = (int) $b['reste'];
                    $exact = $donReste * ($resteBesoin / $totalBesoins);
                    $base = (int) floor($exact);
                    $remainder = $exact - $base;

                    $allocations[(int) $b['id_besoin']] = $base;
                    $remainders[] = [
                        'id_besoin' => (int) $b['id_besoin'],
                        'remainder' => $remainder,
                        'reste' => $resteBesoin
                    ];
                    $used += $base;
                }

                $remaining = $donReste - $used;
                usort($remainders, function ($a, $b) {
                    if ($a['remainder'] === $b['remainder']) {
                        return $a['id_besoin'] <=> $b['id_besoin'];
                    }
                    return $b['remainder'] <=> $a['remainder'];
                });

                while ($remaining > 0) {
                    $progress = false;
                    foreach ($remainders as $item) {
                        if ($remaining <= 0) {
                            break;
                        }
                        $idBesoin = (int) $item['id_besoin'];
                        if ($allocations[$idBesoin] < $item['reste']) {
                            $allocations[$idBesoin] += 1;
                            $remaining -= 1;
                            $progress = true;
                        }
                    }
                    if (!$progress) {
                        break;
                    }
                }
            }

            $totalAttribue = 0;
            $totalAttributions = 0;
            foreach ($allocations as $idBesoin => $qty) {
                $quantite = (int) $qty;
                if ($quantite <= 0) {
                    continue;
                }
                $this->createAttribution($donId, $idBesoin, $quantite);
                $this->updateBesoinQuantite($idBesoin, $quantite);
                $totalAttribue += $quantite;
                $totalAttributions += 1;
            }

            if ($totalAttribue > 0) {
                $this->updateDonQuantite($donId, $totalAttribue);
            }

            $this->db->commit();
            return [
                'attributions' => $totalAttributions,
                'quantite' => $totalAttribue
            ];
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // public function getAllVilles()
    // {
    //     $stmt = $this->db->prepare("SELECT * FROM ville");
    //     $stmt->execute();
    //     return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    // }

    // public function getAllBesoins()
    // {
    //     $stmt = $this->db->prepare("SELECT * FROM besoin");
    //     $stmt->execute();
    //     return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    // }

    // public function getAllDons()
    // {
    //     $stmt = $this->db->prepare("SELECT * FROM don");
    //     $stmt->execute();
    //     return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    // }

}