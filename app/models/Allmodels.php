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
        $sql = "INSERT INTO besoin (libelle_besoin, type_besoin, quantite, prix_unitaire, id_ville) 
                VALUES (?, ?, ?, ?, ?)";
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

    // ==============================
    // TYPE DE DON
    // ==============================
    public function getAllTypes()
    {
        // Les types de don sont des ENUM dans la table don
        $types = [
            ['id' => 'nature', 'name' => 'Nature'],
            ['id' => 'materiaux', 'name' => 'Matériaux'],
            ['id' => 'argent', 'name' => 'Argent']
        ];
        return $types;
    }

<<<<<<< HEAD
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
                    b.libelle_besoin,
                    b.type_besoin,
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
                LEFT JOIN attribution a ON a.id_besoin = b.id_besoin
                GROUP BY v.id_ville, v.nom_ville, b.id_besoin, b.libelle_besoin, 
                         b.type_besoin, b.quantite, b.prix_unitaire, b.date_besoin
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
                    d.type_don,
                    (a.quantite * b.prix_unitaire) AS montant
                FROM attribution a
                JOIN don d ON d.id_don = a.id_don
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
                    d.type_don,
                    (d.quantite + COALESCE(SUM(a.quantite), 0)) AS quantite_initiale,
                    d.date_don,
                    COALESCE(SUM(a.quantite), 0) AS quantite_attribuee,
                    d.quantite AS quantite_restante
                FROM don d
                LEFT JOIN attribution a ON a.id_don = d.id_don
                GROUP BY d.id_don, d.libelle_don, d.type_don, d.quantite, d.date_don
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
                    b.libelle_besoin,
                    b.type_besoin,
                    b.quantite,
                    b.prix_unitaire,
                    b.date_besoin,
                    v.nom_ville,
                    DATEDIFF(NOW(), b.date_besoin) AS jours_attente,
                    COALESCE(SUM(a.quantite), 0) AS quantite_recue
                FROM besoin b
                JOIN ville v ON v.id_ville = b.id_ville
                LEFT JOIN attribution a ON a.id_besoin = b.id_besoin
                GROUP BY b.id_besoin, b.libelle_besoin, b.type_besoin, b.quantite, 
                         b.prix_unitaire, b.date_besoin, v.nom_ville
                HAVING quantite_recue = 0 AND b.quantite > 0 AND jours_attente >= ?
                ORDER BY jours_attente DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$jours]);
=======
     public function getBesoinsRestants($idVille = null)
    {
        $sql = "SELECT * FROM v_besoins_restants WHERE reste > 0";
        
        if ($idVille !== null) {
            $sql .= " AND id_ville = :id_ville";
        }
        
        $sql .= " ORDER BY date_besoin ASC, id_besoin ASC";
        
        $stmt = $this->db->prepare($sql);
        if ($idVille !== null) {
            $stmt->bindValue(':id_ville', $idVille, \PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getQuantiteSatisfaite($idBesoin)
    {
        $sql = "SELECT quantite_satisfaite FROM v_besoins_restants WHERE id_besoin = :id_besoin";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_besoin', $idBesoin, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) ($result['quantite_satisfaite'] ?? 0);
    }

    public function verifierMontantSuffisant($idDon, $montantNecessaire)
    {
        $sql = "SELECT reste_argent FROM v_dons_argent_restants WHERE id_don = :id_don";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_don', $idDon, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$result) {
            return false;
        }
        
        return (float) $result['reste_argent'] >= $montantNecessaire;
    }

    public function getMontantRestantDon($idDon)
    {
        $sql = "SELECT reste_argent FROM v_dons_argent_restants WHERE id_don = :id_don";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_don', $idDon, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $result ? (float) $result['reste_argent'] : 0;
    }
   public function createAttribution($idDon, $idBesoin, $quantite, $montant, $frais, $montantTotal)
    {
        $sql = "INSERT INTO achat (id_don, id_besoin, quantite, montant, frais, montant_total, date_achat)
                VALUES (:id_don, :id_besoin, :quantite, :montant, :frais, :montant_total, CURRENT_TIMESTAMP)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_don', $idDon, \PDO::PARAM_INT);
        $stmt->bindValue(':id_besoin', $idBesoin, \PDO::PARAM_INT);
        $stmt->bindValue(':quantite', $quantite, \PDO::PARAM_INT);
        $stmt->bindValue(':montant', $montant, \PDO::PARAM_STR);
        $stmt->bindValue(':frais', $frais, \PDO::PARAM_STR);
        $stmt->bindValue(':montant_total', $montantTotal, \PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    /**
     * Récupère le récapitulatif global des besoins
     * @return array Totaux, satisfaits et restants
     */
    public function getTotalSatisfait()
    {
        $sql = "SELECT * FROM v_recap_global";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return [
            'montant_total' => (float) ($result['montant_total'] ?? 0),
            'montant_satisfait' => (float) ($result['montant_satisfait'] ?? 0),
            'montant_restant' => (float) ($result['montant_restant'] ?? 0)
        ];
    }

    public function getDonsArgentRestants()
    {
        $sql = "SELECT * FROM v_dons_argent_restants WHERE reste_argent > 0 ORDER BY date_don ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère le pourcentage de frais depuis la configuration
     * @return float Pourcentage de frais (ex: 10 pour 10%)
     */
    public function getFraisConfig()
    {
        $sql = "SELECT valeur_config FROM config WHERE cle_config = 'frais_achat'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $result ? (float) $result['valeur_config'] : 0;
    }

    /**
     * Met à jour ou insère la configuration des frais
     * @param float $frais Pourcentage de frais
     * @return bool Succès de l'opération
     */
    public function setFraisConfig($frais)
    {
        $sql = "INSERT INTO config (cle_config, valeur_config) 
                VALUES ('frais_achat', :frais)
                ON DUPLICATE KEY UPDATE valeur_config = :frais";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':frais', $frais, \PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Récupère tous les achats avec détails (via vue)
     * @param int|null $idVille Filtrer par ville (optionnel)
     * @return array Liste des achats avec détails
     */
    public function getAchatsDetails($idVille = null)
    {
        $sql = "SELECT * FROM v_achats_details";
        
        if ($idVille !== null) {
            $sql .= " WHERE id_ville = :id_ville";
        }
        
        $sql .= " ORDER BY date_achat DESC";
        
        $stmt = $this->db->prepare($sql);
        if ($idVille !== null) {
            $stmt->bindValue(':id_ville', $idVille, \PDO::PARAM_INT);
        }
        $stmt->execute();
>>>>>>> 406354e (Les modfication v2 pour demain  à revoir les fonctionalité)
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
