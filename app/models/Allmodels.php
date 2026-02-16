<?php

namespace app\models;




private $db;
public function __construct($db)
{
    $this->db = $db;
}
class Ville
{
    private $id_ville;
    private $nom_ville;

    public function getIdVille()
    {
        return $this->id_ville;
    }

    public function setIdVille($id_ville)
    {
        $this->id_ville = $id_ville;
    }

    public function getNomVille()
    {
        return $this->nom_ville;
    }

    public function setNomVille($nom_ville)
    {
        $this->nom_ville = $nom_ville;
    }
}


/**
 * ============================
 * MODELE BESOIN
 * ============================
 */
class Besoin
{
    private $id_besoin;
    private $libelle_besoin;
    private $type_besoin;
    private $quantite;
    private $prix_unitaire;
    private $id_ville;


    public function getIdBesoin()
    {
        return $this->id_besoin;
    }

    public function setIdBesoin($id_besoin)
    {
        $this->id_besoin = $id_besoin;
    }

    public function getLibelleBesoin()
    {
        return $this->libelle_besoin;
    }

    public function setLibelleBesoin($libelle_besoin)
    {
        $this->libelle_besoin = $libelle_besoin;
    }

    public function getTypeBesoin()
    {
        return $this->type_besoin;
    }

    public function setTypeBesoin($type_besoin)
    {
        $this->type_besoin = $type_besoin;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }

    public function getPrixUnitaire()
    {
        return $this->prix_unitaire;
    }

    public function setPrixUnitaire($prix_unitaire)
    {
        $this->prix_unitaire = $prix_unitaire;
    }

    public function getIdVille()
    {
        return $this->id_ville;
    }

    public function setIdVille($id_ville)
    {
        $this->id_ville = $id_ville;
    }
    public function insertBesoin($pdo)
    {
        $stmt = $pdo->prepare("INSERT INTO besoins (libelle_besoin, type_besoin, quantite, prix_unitaire, id_ville) VALUES (:libelle_besoin, :type_besoin, :quantite, :prix_unitaire, :id_ville)");
        $stmt->bindParam(':libelle_besoin', $this->libelle_besoin);
        $stmt->bindParam(':type_besoin', $this->type_besoin);
        $stmt->bindParam(':quantite', $this->quantite);
        $stmt->bindParam(':prix_unitaire', $this->prix_unitaire);
        $stmt->bindParam(':id_ville', $this->id_ville);
        return $stmt->execute();
    }
    public function getBesoin($pdo, $id_besoin)
    {
        $stmt = $pdo->prepare("SELECT * FROM besoins WHERE id_besoin = :id_besoin");
        $stmt->bindParam(':id_besoin', $id_besoin);
        $stmt->execute();
        return $stmt->fetch();
    }
}


/**
 * ============================
 * MODELE DON
 * ============================
 */
class Don
{
    private $id_don;
    private $libelle_don;
    private $type_don;
    private $quantite;
    private $date_don;

    public function getIdDon()
    {
        return $this->id_don;
    }

    public function setIdDon($id_don)
    {
        $this->id_don = $id_don;
    }

    public function getLibelleDon()
    {
        return $this->libelle_don;
    }

    public function setLibelleDon($libelle_don)
    {
        $this->libelle_don = $libelle_don;
    }

    public function getTypeDon()
    {
        return $this->type_don;
    }

    public function setTypeDon($type_don)
    {
        $this->type_don = $type_don;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }

    public function getDateDon()
    {
        return $this->date_don;
    }

    public function setDateDon($date_don)
    {
        $this->date_don = $date_don;
    }
}


/**
 * ============================
 * MODELE ATTRIBUTION
 * ============================
 */
class Attribution
{
    private $id_attribution;
    private $id_don;
    private $id_besoin;
    private $quantite;
    private $date_attribution;

    public function getIdAttribution()
    {
        return $this->id_attribution;
    }

    public function setIdAttribution($id_attribution)
    {
        $this->id_attribution = $id_attribution;
    }

    public function getIdDon()
    {
        return $this->id_don;
    }

    public function setIdDon($id_don)
    {
        $this->id_don = $id_don;
    }

    public function getIdBesoin()
    {
        return $this->id_besoin;
    }

    public function setIdBesoin($id_besoin)
    {
        $this->id_besoin = $id_besoin;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }

    public function getDateAttribution()
    {
        return $this->date_attribution;
    }

    public function setDateAttribution($date_attribution)
    {
        $this->date_attribution = $date_attribution;
    }
}
<?php

namespace app\models;

/**
 * ============================
 * MODELE VILLE
 * ============================
 */
class Ville
{
    private $id_ville;
    private $nom_ville;

    public function getIdVille()
    {
        return $this->id_ville;
    }

    public function setIdVille($id_ville)
    {
        $this->id_ville = $id_ville;
    }

    public function getNomVille()
    {
        return $this->nom_ville;
    }

    public function setNomVille($nom_ville)
    {
        $this->nom_ville = $nom_ville;
    }
}


/**
 * ============================
 * MODELE BESOIN
 * ============================
 */
class Besoin
{
    private $id_besoin;
    private $libelle_besoin;
    private $type_besoin;
    private $quantite;
    private $prix_unitaire;
    private $id_ville;

    public function getIdBesoin()
    {
        return $this->id_besoin;
    }

    public function setIdBesoin($id_besoin)
    {
        $this->id_besoin = $id_besoin;
    }

    public function getLibelleBesoin()
    {
        return $this->libelle_besoin;
    }

    public function setLibelleBesoin($libelle_besoin)
    {
        $this->libelle_besoin = $libelle_besoin;
    }

    public function getTypeBesoin()
    {
        return $this->type_besoin;
    }

    public function setTypeBesoin($type_besoin)
    {
        $this->type_besoin = $type_besoin;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }

    public function getPrixUnitaire()
    {
        return $this->prix_unitaire;
    }

    public function setPrixUnitaire($prix_unitaire)
    {
        $this->prix_unitaire = $prix_unitaire;
    }

    public function getIdVille()
    {
        return $this->id_ville;
    }

    public function setIdVille($id_ville)
    {
        $this->id_ville = $id_ville;
    }
}


/**
 * ============================
 * MODELE DON
 * ============================
 */
class Don
{
    private $id_don;
    private $libelle_don;
    private $type_don;
    private $quantite;
    private $date_don;

    public function getIdDon()
    {
        return $this->id_don;
    }

    public function setIdDon($id_don)
    {
        $this->id_don = $id_don;
    }

    public function getLibelleDon()
    {
        return $this->libelle_don;
    }

    public function setLibelleDon($libelle_don)
    {
        $this->libelle_don = $libelle_don;
    }

    public function getTypeDon()
    {
        return $this->type_don;
    }

    public function setTypeDon($type_don)
    {
        $this->type_don = $type_don;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }

    public function getDateDon()
    {
        return $this->date_don;
    }

    public function setDateDon($date_don)
    {
        $this->date_don = $date_don;
    }
}


/**
 * ============================
 * MODELE ATTRIBUTION
 * ============================
 */
class Attribution
{
    private $id_attribution;
    private $id_don;
    private $id_besoin;
    private $quantite;
    private $date_attribution;

    public function getIdAttribution()
    {
        return $this->id_attribution;
    }

    public function setIdAttribution($id_attribution)
    {
        $this->id_attribution = $id_attribution;
    }

    public function getIdDon()
    {
        return $this->id_don;
    }

    public function setIdDon($id_don)
    {
        $this->id_don = $id_don;
    }

    public function getIdBesoin()
    {
        return $this->id_besoin;
    }

    public function setIdBesoin($id_besoin)
    {
        $this->id_besoin = $id_besoin;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }

    public function getDateAttribution()
    {
        return $this->date_attribution;
    }

    public function setDateAttribution($date_attribution)
    {
        $this->date_attribution = $date_attribution;
    }
}
