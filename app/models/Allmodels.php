<?php

namespace app\models;

class AllModels
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // ==============================
    // VILLE
    // ==============================
    public function countVille()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM ville");
        $stmt->execute(); // obligatoire
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getAllVilles()
    {
        $stmt = $this->db->prepare("SELECT * FROM ville");
        $stmt->execute(); // obligatoire
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getVillesBesoins() {
        $st = $this->db->prepare("SELECT * FROM v_villeBesoin");
        $st->execute();
        return $st->fetchAll();
    }

    // ==============================
    // BESOIN
    // ==============================
    public function insertBesoin($data)
    {
        $sql = "INSERT INTO besoin (libelle_besoin, type_besoin, quantite, prix_unitaire, id_ville) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getAllBesoin()
    {
        $stmt = $this->db->prepare("SELECT * FROM besoin ORDER BY date_besoin ASC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countBesoin()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM besoin");
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // ==============================
    // DON
    // ==============================
    public function countDon()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM don");
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getAllDon()
    {
        $stmt = $this->db->prepare("SELECT * FROM don");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // ==============================
    // ATTRIBUTION
    // ==============================
    public function getAllAttributions()
    {
        $stmt = $this->db->prepare("SELECT * FROM attribution");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countAttribution()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM attribution");
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'];
    }

}
