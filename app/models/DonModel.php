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

    public function getAllProduits()
    {
        $stmt = $this->db->prepare("SELECT p.id_produit, p.nom_produit, p.id_type, t.nom_type FROM produit p JOIN type t ON t.id_type = p.id_type ORDER BY t.nom_type, p.nom_produit");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createDon($description, $id_produit, $quantites)
    {
        $sql = "INSERT INTO don (libelle_don, id_produit, quantite, date_don) 
                VALUES (?, ?, ?, CURDATE())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$description, $id_produit, $quantites]);
    }

    /**
     * Cherche un produit par son nom (insensible à la casse)
     */
    public function findProduitByName($nom_produit)
    {
        $stmt = $this->db->prepare("SELECT id_produit, nom_produit, id_type FROM produit WHERE LOWER(nom_produit) = LOWER(?)");
        $stmt->execute([$nom_produit]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Crée un nouveau produit et retourne son id
     */
    public function createProduit($nom_produit, $id_type)
    {
        $stmt = $this->db->prepare("INSERT INTO produit (nom_produit, id_type) VALUES (?, ?)");
        $stmt->execute([$nom_produit, $id_type]);
        return $this->db->lastInsertId();
    }
}