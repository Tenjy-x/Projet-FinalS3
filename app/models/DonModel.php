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
        // Les types sont définis comme ENUM dans la table don
        return [
            ['id' => 'nature', 'name' => 'Nature'],
            ['id' => 'materiaux', 'name' => 'Matériaux'],
            ['id' => 'argent', 'name' => 'Argent']
        ];
    }

    public function createDon($description, $type, $quantites)
    {
        $sql = "INSERT INTO don (libelle_don, type_don, quantite, date_don) 
                VALUES (?, ?, ?, CURDATE())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$description, $type, $quantites]);
    }
}