-- =========================================
-- SUPPRESSION ET CREATION DE LA BASE
-- =========================================
DROP DATABASE IF EXISTS BNGRC;
CREATE DATABASE BNGRC;
USE BNGRC;

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
    libelle_besoin VARCHAR(255) NOT NULL,
    type_besoin ENUM('nature','materiaux','argent') NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    date_besoin TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_ville INT,
    FOREIGN KEY(id_ville) REFERENCES ville(id_ville)
);
INSERT INTO besoin (libelle_besoin, type_besoin, quantite, prix_unitaire, date_besoin, id_ville) VALUES
('Riz', 'nature',  1000, 1.50, '2026-02-10', 1),
('Huile', 'nature', 500, 2.00, '2026-02-11', 1),
('Tôles', 'materiaux', 200, 5.00, '2026-02-12', 2),
('Clous', 'materiaux', 5000, 0.10, '2026-02-12', 2),
('Fonds pour école', 'argent', 100000, 1.00, '2026-02-13', 3),
('Médicaments', 'nature', 200, 3.50, '2026-02-14', 4),
('Ciment', 'materiaux', 1500, 0.80, '2026-02-15', 5);


-- =========================================
-- TABLE DON
-- =========================================
CREATE TABLE don(
    id_don INT PRIMARY KEY AUTO_INCREMENT,
    libelle_don VARCHAR(255) NOT NULL,
    type_don ENUM('nature','materiaux','argent') NOT NULL,
    quantite INT NOT NULL,  
    date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO don (libelle_don, type_don, quantite, date_don) VALUES
('Don de riz - Association X', 'nature', 500, '2026-02-10'),
('Don de tôles - Entreprise Y', 'materiaux', 100, '2026-02-12'),
('Don financier - Privé', 'argent', 50000, '2026-02-14'),
('Don huile - ONG Z', 'nature', 300, '2026-02-15'),
('Don de clous - Magasin', 'materiaux', 3000, '2026-02-13'),
('Don financier - Collecte', 'argent', 75000, '2026-02-11'),
('Don de ciment - Fournisseur', 'materiaux', 800, '2026-02-14');
-- =========================================
-- TABLE ATTRIBUTION
-- =========================================
    CREATE TABLE attribution(
        id_attribution INT PRIMARY KEY AUTO_INCREMENT,
        id_don INT,
        id_besoin INT,
        quantite INT NOT NULL,
        type_effectif ENUM('nature','materiaux','argent') NOT NULL,
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
    b.libelle_besoin,
    b.type_besoin,
    b.quantite,
    b.prix_unitaire,
    (b.quantite * b.prix_unitaire) AS montant_total
FROM ville v
JOIN besoin b ON b.id_ville = v.id_ville;

-- =========================================
-- VIEW ATTRIBUTION_BESOIN_DON
-- =========================================
CREATE OR REPLACE VIEW v_attribution_details AS
SELECT
    a.id_attribution,
    a.quantite AS quantite_attribuee,
    a.date_attribution,
    b.libelle_besoin,
    b.type_besoin,
    b.quantite AS quantite_besoin,
    b.prix_unitaire,
    d.libelle_don,
    d.type_don,
    d.quantite AS quantite_don
FROM attribution a
JOIN besoin b ON b.id_besoin = a.id_besoin
JOIN don d ON d.id_don = a.id_don;







