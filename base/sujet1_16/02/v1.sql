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

    INSERT INTO type (nom_type) VALUES
    ('nature'),
    ('materiaux'),
    ('argent');

    -- =========================================
    -- TABLE PRODUIT
    -- =========================================
    CREATE TABLE produit(
        id_produit INT PRIMARY KEY AUTO_INCREMENT,
        nom_produit VARCHAR(255) NOT NULL,
        id_type INT NOT NULL,
        FOREIGN KEY(id_type) REFERENCES type(id_type)
    );
    
    INSERT INTO produit (nom_produit, id_type) VALUES
    ('Riz', 1),
    ('Huile', 1),
    ('Tôles', 2),
    ('Clous', 2),
    ('Fonds pour école', 3),
    ('Médicaments', 1),
    ('Ciment', 2);

    -- =========================================
    -- TABLE VILLE
    -- =========================================
    CREATE TABLE ville(
        id_ville INT PRIMARY KEY AUTO_INCREMENT,
        nom_ville VARCHAR(255) NOT NULL
    );

    INSERT INTO ville (nom_ville) VALUES
    ('Antananarivo'),
    ('Toliara'),
    ('Antsirabe'),
    ('Mahajanga'),
    ('Toliara');

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
    INSERT INTO besoin (id_produit, quantite, prix_unitaire, date_besoin, id_ville) VALUES
    (1, 1000, 1.50, '2026-02-10', 1),
    (2, 500, 2.00, '2026-02-11', 1),
    (3, 200, 5.00, '2026-02-12', 2);


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

    INSERT INTO don (libelle_don, id_produit, quantite, date_don) VALUES
    ('Don de riz - Association X', 1, 500, '2026-02-10'),
    ('Don de tôles - Entreprise Y', 3, 100, '2026-02-12'),
    ('Don huile - ONG Z', 2, 300, '2026-02-15');

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







