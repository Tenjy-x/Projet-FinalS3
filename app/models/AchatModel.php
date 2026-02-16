<?php

namespace app\models;

class AchatModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Besoins restants (nature & matériaux), filtrables par ville
     */
    public function getBesoinsRestants(?int $id_ville = null)
    {
        $sql = "SELECT b.*, v.nom_ville, t.nom_type,
                    b.quantite - COALESCE((SELECT SUM(a.quantite) FROM achat a WHERE a.id_besoin = b.id_besoin), 0) AS quantite_restante
                FROM besoin b
                JOIN ville v ON v.id_ville = b.id_ville
                JOIN type t ON t.id_type = b.id_type
                WHERE t.nom_type IN ('nature', 'materiaux')";
        
        $params = [];
        if ($id_ville !== null) {
            $sql .= " AND b.id_ville = ?";
            $params[] = $id_ville;
        }
        $sql .= " HAVING quantite_restante > 0";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Solde argent = total dons argent - total dépensé en achats
     */
    public function getSoldeArgent()
    {
        $sql = "SELECT 
                    COALESCE((SELECT SUM(d.quantite) FROM don d JOIN type t ON t.id_type = d.id_type WHERE t.nom_type = 'argent'), 0) 
                    - COALESCE((SELECT SUM(montant_total) FROM achat), 0) AS solde";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (float) $result['solde'];
    }

    /**
     * Lire le frais d'achat depuis la table config
     */
    public function getFraisAchat()
    {
        $stmt = $this->db->prepare("SELECT valeur_config FROM config WHERE cle_config = 'frais_achat'");
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? (float) $result['valeur_config'] : 10.0;
    }

    /**
     * Modifier le frais d'achat dans la table config
     */
    public function setFraisAchat(float $frais)
    {
        $stmt = $this->db->prepare("INSERT INTO config (cle_config, valeur_config) VALUES ('frais_achat', ?) 
                                     ON DUPLICATE KEY UPDATE valeur_config = ?");
        return $stmt->execute([$frais, $frais]);
    }

    /**
     * Récupérer un besoin avec sa quantité restante
     */
    public function getBesoinById(int $id_besoin)
    {
        $sql = "SELECT b.*, t.nom_type,
                    b.quantite - COALESCE((SELECT SUM(a.quantite) FROM achat a WHERE a.id_besoin = b.id_besoin), 0) AS quantite_restante
                FROM besoin b
                JOIN type t ON t.id_type = b.id_type
                WHERE b.id_besoin = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_besoin]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Trouver un don en argent qui a encore du solde
     */
    public function getDonArgentDisponible()
    {
        $sql = "SELECT d.id_don, d.libelle_don, 
                    d.quantite - COALESCE((SELECT SUM(a.montant_total) FROM achat a WHERE a.id_don = d.id_don), 0) AS solde_restant
                FROM don d 
                JOIN type t ON t.id_type = d.id_type
                WHERE t.nom_type = 'argent'
                HAVING solde_restant > 0
                ORDER BY d.date_don ASC
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Insérer un achat
     */
    public function creerAchat(int $id_besoin, int $id_don, int $quantite, float $montant, float $frais, float $montant_total)
    {
        $sql = "INSERT INTO achat (id_besoin, id_don, quantite, montant, frais, montant_total) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_besoin, $id_don, $quantite, $montant, $frais, $montant_total]);
    }

    /**
     * Historique des achats
     */
    public function getAllAchats()
    {
        $sql = "SELECT a.*, b.libelle_besoin, t.nom_type, v.nom_ville
                FROM achat a
                JOIN besoin b ON b.id_besoin = a.id_besoin
                JOIN type t ON t.id_type = b.id_type
                JOIN ville v ON v.id_ville = b.id_ville
                ORDER BY a.date_achat DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
