<?php

namespace app\models;
class DonModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllTypes()
    {
        $stmt = $this->db->prepare("SELECT id_type, nom_type FROM type");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createDon($description, $id_type, $quantites)
    {
        $sql = "INSERT INTO don (libelle_don, id_type, quantite, date_don) 
                VALUES (?, ?, ?, CURDATE())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$description, $id_type, $quantites]);
    }
}