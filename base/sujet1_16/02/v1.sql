    -- =========================================
    -- SUPPRESSION ET CREATION DE LA BASE
    -- =========================================
    DROP DATABASE IF EXISTS BNGRC;
    CREATE DATABASE BNGRC;
    USE BNGRC;

    -- =========================================
    -- TABLE TYPE
    -- =========================================
    CREATE TABLE type(
        id_type INT PRIMARY KEY AUTO_INCREMENT,
        nom_type VARCHAR(50) NOT NULL
    );

    INSERT INTO type (id_type, nom_type) VALUES
    (1, 'nature'),
    (2, 'materiaux'),
    (3, 'argent');

    -- =========================================
    -- TABLE PRODUIT
    -- =========================================
    CREATE TABLE produit(
        id_produit INT PRIMARY KEY AUTO_INCREMENT,
        nom_produit VARCHAR(255) NOT NULL,
        id_type INT NOT NULL,
        FOREIGN KEY(id_type) REFERENCES type(id_type)
    );

    INSERT INTO produit (id_produit, nom_produit, id_type) VALUES
    (1, 'Riz', 1),
    (2, 'Huile', 1),
    (3, 'Tôles', 2),
    (4, 'Clous', 2),
    (5, 'Fonds pour école', 3),
    (6, 'Médicaments', 1),
    (7, 'Ciment', 2);

    -- =========================================
    -- TABLE VILLE
    -- =========================================
    CREATE TABLE ville(
        id_ville INT PRIMARY KEY AUTO_INCREMENT,
        nom_ville VARCHAR(255) NOT NULL
    );

    INSERT INTO ville (id_ville, nom_ville) VALUES
    (1, 'Antananarivo'),
    (2, 'Toliara'),
    (3, 'Antsirabe'),
    (4, 'Mahajanga'),
    (5, 'Toliara');

    -- =========================================
    -- TABLE BESOIN
    -- =========================================
    CREATE TABLE besoin(
        id_besoin INT PRIMARY KEY AUTO_INCREMENT,
        id_produit INT NOT NULL,
        quantite INT NOT NULL,
        prix_unitaire DECIMAL(10,2) NOT NULL,
        date_besoin TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        id_ville INT,
        FOREIGN KEY(id_produit) REFERENCES produit(id_produit),
        FOREIGN KEY(id_ville) REFERENCES ville(id_ville)
    );
<<<<<<< HEAD
    INSERT INTO besoin (id_besoin, id_produit, quantite, prix_unitaire, date_besoin, id_ville) VALUES
    (1, 1, 1000, 1.50, '2026-02-13', 1),
    (2, 1, 500, 2.00, '2026-02-11', 3),
    (3, 1, 300, 5.00, '2026-02-12', 2);
=======
    INSERT INTO besoin (id_produit, quantite, prix_unitaire, date_besoin, id_ville) VALUES
    (1, 1000, 1.50, '2026-02-10', 1),
    (1, 500, 2.00, '2026-02-11', 1),
    (3, 200, 5.00, '2026-02-12', 2);
>>>>>>> d3b5791 (modif kely)

    -- =========================================
    -- TABLE DON
    -- =========================================
    CREATE TABLE don(
        id_don INT PRIMARY KEY AUTO_INCREMENT,
        libelle_don VARCHAR(255) NOT NULL,
        id_produit INT NOT NULL,
        quantite INT NOT NULL,  
        date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(id_produit) REFERENCES produit(id_produit)
    );

<<<<<<< HEAD
        INSERT INTO don (id_don, libelle_don, id_produit, quantite, date_don) VALUES
    (1, 'Don de riz - Association X', 1, 200, '2026-02-10');
=======
    INSERT INTO don (libelle_don, id_produit, quantite, date_don) VALUES
    ('Don de riz - Association X', 1, 500, '2026-02-10'),
    ('Don huile - ONG Z', 2, 300, '2026-02-15');
>>>>>>> d3b5791 (modif kely)


    -- =========================================
    -- TABLE ATTRIBUTION
    -- =========================================
        CREATE TABLE attribution(
            id_attribution INT PRIMARY KEY AUTO_INCREMENT,
            id_don INT,
            id_besoin INT,
            quantite INT NOT NULL,
            date_attribution TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(id_don) REFERENCES don(id_don),
            FOREIGN KEY(id_besoin) REFERENCES besoin(id_besoin)
        );

    -- =========================================
    -- TABLE CONFIG
    -- =========================================
    CREATE TABLE config(
        cle_config VARCHAR(50) PRIMARY KEY,
        valeur_config VARCHAR(255) NOT NULL
    );

    INSERT INTO config (cle_config, valeur_config) VALUES ('frais_achat', '10');

    -- =========================================
    -- TABLE ACHAT
    -- =========================================
    CREATE TABLE achat(
        id_achat INT PRIMARY KEY AUTO_INCREMENT,
        id_besoin INT NOT NULL,
        id_don INT NOT NULL,
        quantite INT NOT NULL,
        montant DECIMAL(15,2) NOT NULL,
        frais DECIMAL(15,2) NOT NULL DEFAULT 0,
        montant_total DECIMAL(15,2) NOT NULL,
        date_achat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(id_besoin) REFERENCES besoin(id_besoin),
        FOREIGN KEY(id_don) REFERENCES don(id_don)
    );

    -- =========================================
    -- VIEW VILLE_BESOIN
    -- =========================================
    CREATE OR REPLACE VIEW v_villeBesoin AS
    SELECT
        v.id_ville,
        v.nom_ville,
        b.id_besoin,
        p.nom_produit,
        t.nom_type AS type_besoin,
        b.quantite,
        b.prix_unitaire,
        (b.quantite * b.prix_unitaire) AS montant_total
    FROM ville v
    JOIN besoin b ON b.id_ville = v.id_ville
    JOIN produit p ON p.id_produit = b.id_produit
    JOIN type t ON t.id_type = p.id_type;

    -- =========================================
    -- VIEW ATTRIBUTION_BESOIN_DON
    -- =========================================
    CREATE OR REPLACE VIEW v_attribution_details AS
    SELECT
        a.id_attribution,
        a.quantite AS quantite_attribuee,
        a.date_attribution,
        pb.nom_produit AS nom_produit_besoin,
        tb.nom_type AS type_besoin,
        b.quantite AS quantite_besoin,
        b.prix_unitaire,
        d.libelle_don,
        pd.nom_produit AS nom_produit_don,
        td.nom_type AS type_don,
        d.quantite AS quantite_don
    FROM attribution a
    JOIN besoin b ON b.id_besoin = a.id_besoin
    JOIN don d ON d.id_don = a.id_don
    JOIN produit pb ON pb.id_produit = b.id_produit
    JOIN produit pd ON pd.id_produit = d.id_produit
    JOIN type tb ON tb.id_type = pb.id_type
    JOIN type td ON td.id_type = pd.id_type;

-- =========================================
    -- VUES DE V2
    -- =========================================
CREATE OR REPLACE VIEW v_besoins_restants AS
SELECT 
    b.id_besoin,
    p.nom_produit,
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
INNER JOIN produit p ON p.id_produit = b.id_produit
INNER JOIN type t ON t.id_type = p.id_type
LEFT JOIN attribution a ON a.id_besoin = b.id_besoin
LEFT JOIN achat ac ON ac.id_besoin = b.id_besoin
GROUP BY b.id_besoin, p.nom_produit, t.nom_type, b.quantite, b.prix_unitaire, 
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
INNER JOIN produit p ON p.id_produit = d.id_produit
INNER JOIN type t ON t.id_type = p.id_type
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
    p.nom_produit,
    t.nom_type,
    b.prix_unitaire,
    v.id_ville,
    v.nom_ville
FROM achat ac
INNER JOIN don d ON d.id_don = ac.id_don
INNER JOIN besoin b ON b.id_besoin = ac.id_besoin
INNER JOIN produit p ON p.id_produit = b.id_produit
INNER JOIN type t ON t.id_type = p.id_type
INNER JOIN ville v ON v.id_ville = b.id_ville;




