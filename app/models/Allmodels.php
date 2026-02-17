<?php

namespace app\models;

class AllModels
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function countVille()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM ville");
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getAllVilles()
    {
        $stmt = $this->db->prepare("SELECT * FROM ville");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getVillesBesoins()
    {
        $st = $this->db->prepare("SELECT * FROM v_villeBesoin");
        $st->execute();
        return $st->fetchAll();
    }
    public function insertBesoin($data)
    {
        $sql = "INSERT INTO besoin (id_produit, quantite, prix_unitaire, id_ville) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getAllBesoin()
    {
        $stmt = $this->db->prepare("SELECT * FROM besoin");
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

    public function getAttributionDetails()
    {
        $stmt = $this->db->prepare("SELECT * FROM v_attribution_details");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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

    // ==============================
    // DASHBOARD - Requêtes sans modification de la base
    // ==============================
    
    /**
     * Récupère les besoins avec les quantités attribuées pour chaque ville
     */
    public function getDashboardData()
    {
        $sql = "SELECT 
                    v.id_ville,
                    v.nom_ville,
                    b.id_besoin,
                    p.nom_produit,
                    t.nom_type,
                    (b.quantite + COALESCE(SUM(a.quantite), 0)) AS quantite_besoin,
                    b.prix_unitaire,
                    b.date_besoin,
                    ((b.quantite + COALESCE(SUM(a.quantite), 0)) * b.prix_unitaire) AS montant_besoin,
                    COALESCE(SUM(a.quantite), 0) AS quantite_recue,
                    COALESCE(SUM(a.quantite * b.prix_unitaire), 0) AS montant_recu,
                    b.quantite AS quantite_reste,
                    (b.quantite * b.prix_unitaire) AS montant_reste
                FROM ville v
                JOIN besoin b ON b.id_ville = v.id_ville
                JOIN produit p ON p.id_produit = b.id_produit
                JOIN type t ON t.id_type = p.id_type
                LEFT JOIN attribution a ON a.id_besoin = b.id_besoin
                GROUP BY v.id_ville, v.nom_ville, b.id_besoin, p.nom_produit, 
                         t.nom_type, b.quantite, b.prix_unitaire, b.date_besoin
                ORDER BY v.nom_ville, b.date_besoin";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les attributions pour un besoin spécifique
     */
    public function getAttributionsParBesoin($id_besoin)
    {
        $sql = "SELECT 
                    a.id_attribution,
                    a.date_attribution,
                    a.quantite,
                    d.libelle_don,
                    p.nom_produit,
                    t.nom_type,
                    (a.quantite * b.prix_unitaire) AS montant
                FROM attribution a
                JOIN don d ON d.id_don = a.id_don
                JOIN produit p ON p.id_produit = d.id_produit
                JOIN type t ON t.id_type = p.id_type
                JOIN besoin b ON b.id_besoin = a.id_besoin
                WHERE a.id_besoin = ?
                ORDER BY a.date_attribution DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_besoin]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les dons en attente (non attribués ou partiellement attribués)
     */
    public function getDonsEnAttente()
    {
        $sql = "SELECT 
                    d.id_don,
                    d.libelle_don,
                    p.nom_produit,
                    t.nom_type,
                    (d.quantite + COALESCE(SUM(a.quantite), 0)) AS quantite_initiale,
                    d.date_don,
                    COALESCE(SUM(a.quantite), 0) AS quantite_attribuee,
                    d.quantite AS quantite_restante
                FROM don d
                JOIN produit p ON p.id_produit = d.id_produit
                JOIN type t ON t.id_type = p.id_type
                LEFT JOIN attribution a ON a.id_don = d.id_don
                GROUP BY d.id_don, d.libelle_don, p.nom_produit, t.nom_type, d.quantite, d.date_don
                HAVING d.quantite > 0
                ORDER BY d.date_don ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Statistiques globales
     */
    public function getStatsGlobales()
    {
        $stats = [];
        
        // Nombre de villes
        $stmt = $this->db->prepare("SELECT COUNT(DISTINCT id_ville) as total FROM ville");
        $stmt->execute();
        $stats['nombre_villes'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        
        // Nombre de besoins
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM besoin");
        $stmt->execute();
        $stats['nombre_besoins'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        
        // Nombre de dons
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM don");
        $stmt->execute();
        $stats['nombre_dons'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        
        // Nombre d'attributions
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM attribution");
        $stmt->execute();
        $stats['nombre_attributions'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        
        // Montant total des besoins (quantité restante + attribuée)
        $stmt = $this->db->prepare("SELECT COALESCE(SUM((b.quantite + COALESCE(a.total_attrib, 0)) * b.prix_unitaire), 0) AS total
                                    FROM besoin b
                                    LEFT JOIN (
                                        SELECT id_besoin, SUM(quantite) AS total_attrib
                                        FROM attribution
                                        GROUP BY id_besoin
                                    ) a ON a.id_besoin = b.id_besoin");
        $stmt->execute();
        $stats['montant_total_besoins'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        
        // Montant total reçu
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(a.quantite * b.prix_unitaire), 0) as total 
                                    FROM attribution a 
                                    JOIN besoin b ON a.id_besoin = b.id_besoin");
        $stmt->execute();
        $stats['montant_total_recu'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
        
        // Taux de satisfaction
        if ($stats['montant_total_besoins'] > 0) {
            $stats['taux_satisfaction'] = round(($stats['montant_total_recu'] / $stats['montant_total_besoins']) * 100, 2);
        } else {
            $stats['taux_satisfaction'] = 0;
        }
        
        return $stats;
    }

    /**
     * Besoins urgents (aucun don reçu depuis plus de X jours)
     */
    public function getBesoinsUrgents($jours = 3)
    {
        $sql = "SELECT 
                    b.id_besoin,
                    p.nom_produit,
                    t.nom_type,
                    b.quantite,
                    b.prix_unitaire,
                    b.date_besoin,
                    v.nom_ville,
                    DATEDIFF(NOW(), b.date_besoin) AS jours_attente,
                    COALESCE(SUM(a.quantite), 0) AS quantite_recue
                FROM besoin b
                JOIN ville v ON v.id_ville = b.id_ville
                JOIN produit p ON p.id_produit = b.id_produit
                JOIN type t ON t.id_type = p.id_type
                LEFT JOIN attribution a ON a.id_besoin = b.id_besoin
                GROUP BY b.id_besoin, p.nom_produit, t.nom_type, b.quantite, 
                         b.prix_unitaire, b.date_besoin, v.nom_ville
                HAVING quantite_recue = 0 AND b.quantite > 0 AND jours_attente >= ?
                ORDER BY jours_attente DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$jours]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // ==============================
    // ACHATS - Méthodes pour le module d'achats
    // ==============================

    /**
     * Besoins restants (nature & matériaux), filtrables par ville
     */
    public function getBesoinsRestants(?int $idVille = null)
    {
        $sql = "SELECT b.id_besoin, p.nom_produit, t.nom_type, b.quantite, b.prix_unitaire,
                    b.date_besoin, b.id_ville, v.nom_ville,
                    (b.quantite - COALESCE((SELECT SUM(at.quantite) FROM attribution at WHERE at.id_besoin = b.id_besoin), 0)
                                - COALESCE((SELECT SUM(ac.quantite) FROM achat ac WHERE ac.id_besoin = b.id_besoin), 0)) AS reste,
                    ((b.quantite - COALESCE((SELECT SUM(at.quantite) FROM attribution at WHERE at.id_besoin = b.id_besoin), 0)
                                 - COALESCE((SELECT SUM(ac.quantite) FROM achat ac WHERE ac.id_besoin = b.id_besoin), 0)) * b.prix_unitaire) AS montant_restant
                FROM besoin b
                JOIN ville v ON v.id_ville = b.id_ville
                JOIN produit p ON p.id_produit = b.id_produit
                JOIN type t ON t.id_type = p.id_type";
        
        $params = [];
        if ($idVille !== null && $idVille > 0) {
            $sql .= " WHERE b.id_ville = ?";
            $params[] = $idVille;
        }
        $sql .= " HAVING reste > 0 ORDER BY v.nom_ville, p.nom_produit";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Dons en argent avec montants restants
     */
    public function getDonsArgentRestants()
    {
        $sql = "SELECT d.id_don, d.libelle_don, d.quantite AS montant_initial, d.date_don,
                    COALESCE((SELECT SUM(ac.montant_total) FROM achat ac WHERE ac.id_don = d.id_don), 0) AS montant_utilise,
                    (d.quantite - COALESCE((SELECT SUM(ac.montant_total) FROM achat ac WHERE ac.id_don = d.id_don), 0)) AS reste_argent
                FROM don d
                JOIN produit p ON p.id_produit = d.id_produit
                JOIN type t ON t.id_type = p.id_type
                WHERE t.nom_type = 'argent'
                HAVING reste_argent > 0
                ORDER BY d.date_don ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Lire le pourcentage de frais d'achat depuis la table config
     */
    public function getFraisConfig()
    {
        $stmt = $this->db->prepare("SELECT valeur_config FROM config WHERE cle_config = 'frais_achat'");
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? (float) $result['valeur_config'] : 10.0;
    }

    /**
     * Modifier le pourcentage de frais d'achat
     */
    public function setFraisConfig(float $frais)
    {
        $stmt = $this->db->prepare("INSERT INTO config (cle_config, valeur_config) VALUES ('frais_achat', ?) 
                                     ON DUPLICATE KEY UPDATE valeur_config = ?");
        return $stmt->execute([$frais, $frais]);
    }

    /**
     * Montant restant d'un don en argent
     */
    public function getMontantRestantDon(int $idDon)
    {
        $sql = "SELECT d.quantite - COALESCE((SELECT SUM(ac.montant_total) FROM achat ac WHERE ac.id_don = d.id_don), 0) AS reste
                FROM don d WHERE d.id_don = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idDon]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? (float) $result['reste'] : 0;
    }

    /**
     * Vérifie si un don a assez d'argent pour un montant donné
     */
    public function verifierMontantSuffisant(int $idDon, float $montant)
    {
        return $this->getMontantRestantDon($idDon) >= $montant;
    }

    /**
     * Créer un achat (utilisation d'un don argent pour acheter un besoin)
     */
    public function createAchat(int $idDon, int $idBesoin, int $quantite, float $montant, float $frais, float $montantTotal)
    {
        $sql = "INSERT INTO achat (id_don, id_besoin, quantite, montant, frais, montant_total) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idDon, $idBesoin, $quantite, $montant, $frais, $montantTotal]);
    }

    /**
     * Récapitulatif global (total besoins, satisfait, restant)
     */
    public function getTotalSatisfait()
    {
        $sql = "SELECT 
                    COALESCE(SUM(b.quantite * b.prix_unitaire), 0) AS montant_total,
                    COALESCE(SUM(LEAST(COALESCE(a.qte_attr, 0) + COALESCE(ac.qte_achat, 0), b.quantite) * b.prix_unitaire), 0) AS montant_satisfait,
                    COALESCE(SUM(GREATEST(b.quantite - COALESCE(a.qte_attr, 0) - COALESCE(ac.qte_achat, 0), 0) * b.prix_unitaire), 0) AS montant_restant
                FROM besoin b
                LEFT JOIN (SELECT id_besoin, SUM(quantite) AS qte_attr FROM attribution GROUP BY id_besoin) a ON a.id_besoin = b.id_besoin
                LEFT JOIN (SELECT id_besoin, SUM(quantite) AS qte_achat FROM achat GROUP BY id_besoin) ac ON ac.id_besoin = b.id_besoin";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Historique des achats, filtrable par ville
     */
    public function getAchatsDetails(?int $idVille = null)
    {
        $sql = "SELECT ac.*, d.libelle_don, p.nom_produit, t.nom_type, b.prix_unitaire, v.id_ville, v.nom_ville
                FROM achat ac
                JOIN don d ON d.id_don = ac.id_don
                JOIN besoin b ON b.id_besoin = ac.id_besoin
                JOIN produit p ON p.id_produit = b.id_produit
                JOIN type t ON t.id_type = p.id_type
                JOIN ville v ON v.id_ville = b.id_ville";
        
        $params = [];
        if ($idVille !== null && $idVille > 0) {
            $sql .= " WHERE b.id_ville = ?";
            $params[] = $idVille;
        }
        $sql .= " ORDER BY ac.date_achat DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
