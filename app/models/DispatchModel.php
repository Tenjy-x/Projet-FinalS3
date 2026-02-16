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
        $st = $this->db->prepare("INSERT INTO attribution(id_don , id_besoin , quantite, type_effectif) VALUES (? , ? , ?, ?)");
        $st->execute($data);
    }

    private function getDonsWithReste()
    {
        $sql = "SELECT d.*, (d.quantite - IFNULL(SUM(a.quantite), 0)) AS reste
                FROM don d
                LEFT JOIN attribution a ON a.id_don = d.id_don
                GROUP BY d.id_don
                HAVING reste > 0
                ORDER BY d.date_don ASC, d.id_don ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getBesoinsWithReste()
    {
        $sql = "SELECT b.*, (b.quantite - IFNULL(SUM(a.quantite), 0)) AS reste
                FROM besoin b
                LEFT JOIN attribution a ON a.id_besoin = b.id_besoin
                GROUP BY b.id_besoin
                HAVING reste > 0
                ORDER BY b.date_besoin ASC, b.id_besoin ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function createAttribution($idDon, $idBesoin, $quantite, $typeEffectif)
    {
        $sql = "INSERT INTO attribution (id_don, id_besoin, quantite, type_effectif, date_attribution)
                VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idDon, $idBesoin, $quantite, $typeEffectif]);
    }

    private function updateDonQuantite($idDon, $quantiteUtilisee)
    {
        $sql = "UPDATE don SET quantite = quantite - ? WHERE id_don = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$quantiteUtilisee, $idDon]);
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
                    $typeBesoin = $besoin['type_besoin'];
                    $typeDon = $don['type_don'];

                    // Seulement même type
                    if ($typeBesoin !== $typeDon) {
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

                    $this->createAttribution($don['id_don'], $besoin['id_besoin'], $quantiteAttribuee, $typeDon);
                    $this->updateDonQuantite($don['id_don'], $quantiteAttribuee);

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