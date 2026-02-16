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
        $stmt = $this->db->prepare("SELECT * FROM type_don");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createDon($description, $type, $quantites, $date)
    {
        $sql = "INSERT INTO don (description, type, quantites, date) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$description, $type, $quantites, $date]);
    }
}