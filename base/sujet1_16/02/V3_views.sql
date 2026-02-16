-- =========================================
-- VUES POUR SIMPLIFIER LES REQUETES
-- =========================================

-- Vue des besoins avec quantités restantes
CREATE OR REPLACE VIEW v_besoins_restants AS
SELECT 
    b.id_besoin,
    b.libelle_besoin,
    t.nom_type,
    b.quantite,
    b.prix_unitaire,
    b.date_besoin,
    b.id_ville,
    v.nom_ville,
    (b.quantite - IFNULL(SUM(a.quantite), 0) - IFNULL(SUM(ac.quantite), 0)) AS reste,
    (b.quantite * b.prix_unitaire) AS montant_total,
    ((b.quantite - IFNULL(SUM(a.quantite), 0) - IFNULL(SUM(ac.quantite), 0)) * b.prix_unitaire) AS montant_restant,
    (IFNULL(SUM(a.quantite), 0) + IFNULL(SUM(ac.quantite), 0)) AS quantite_satisfaite
FROM besoin b
INNER JOIN ville v ON v.id_ville = b.id_ville
INNER JOIN type t ON t.id_type = b.id_type
LEFT JOIN attribution a ON a.id_besoin = b.id_besoin
LEFT JOIN achat ac ON ac.id_besoin = b.id_besoin
GROUP BY b.id_besoin, b.libelle_besoin, t.nom_type, b.quantite, b.prix_unitaire, 
         b.date_besoin, b.id_ville, v.nom_ville;

-- Vue des dons en argent avec montants restants
CREATE OR REPLACE VIEW v_dons_argent_restants AS
SELECT 
    d.id_don,
    d.libelle_don,
    d.quantite AS montant_initial,
    d.date_don,
    IFNULL(SUM(ac.montant_total), 0) AS montant_utilise,
    (d.quantite - IFNULL(SUM(ac.montant_total), 0)) AS reste_argent
FROM don d
LEFT JOIN achat ac ON ac.id_don = d.id_don
INNER JOIN type t ON t.id_type = d.id_type
WHERE t.nom_type = 'argent'
GROUP BY d.id_don, d.libelle_don, d.quantite, d.date_don;

-- Vue du récapitulatif global
CREATE OR REPLACE VIEW v_recap_global AS
SELECT 
    SUM(b.quantite * b.prix_unitaire) AS montant_total,
    SUM((IFNULL(a.quantite_attribuee, 0) + IFNULL(ac.quantite_achetee, 0)) * b.prix_unitaire) AS montant_satisfait,
    SUM((b.quantite - IFNULL(a.quantite_attribuee, 0) - IFNULL(ac.quantite_achetee, 0)) * b.prix_unitaire) AS montant_restant
FROM besoin b
LEFT JOIN (
    SELECT id_besoin, SUM(quantite) AS quantite_attribuee
    FROM attribution
    GROUP BY id_besoin
) a ON a.id_besoin = b.id_besoin
LEFT JOIN (
    SELECT id_besoin, SUM(quantite) AS quantite_achetee
    FROM achat
    GROUP BY id_besoin
) ac ON ac.id_besoin = b.id_besoin;

-- Vue des achats avec détails
CREATE OR REPLACE VIEW v_achats_details AS
SELECT 
    ac.id_achat,
    ac.id_don,
    ac.id_besoin,
    ac.quantite,
    ac.montant,
    ac.frais,
    ac.montant_total,
    ac.date_achat,
    d.libelle_don,
    b.libelle_besoin,
    t.nom_type,
    b.prix_unitaire,
    v.id_ville,
    v.nom_ville
FROM achat ac
INNER JOIN don d ON d.id_don = ac.id_don
INNER JOIN besoin b ON b.id_besoin = ac.id_besoin
INNER JOIN type t ON t.id_type = b.id_type
INNER JOIN ville v ON v.id_ville = b.id_ville;
