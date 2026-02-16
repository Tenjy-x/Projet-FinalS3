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
-- TABLE BESOIN
-- =========================================
CREATE TABLE besoin(
    id_besoin INT PRIMARY KEY AUTO_INCREMENT,
    libelle_besoin VARCHAR(255) NOT NULL,
    id_type INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    date_besoin TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_ville INT,
    FOREIGN KEY(id_ville) REFERENCES ville(id_ville),
    FOREIGN KEY(id_type) REFERENCES type(id_type)
);
INSERT INTO besoin (libelle_besoin, id_type, quantite, prix_unitaire, id_ville) VALUES
('Riz', 1,  1000, 1.50, 1),
('Huile', 1, 500, 2.00, 1),
('Tôles', 2, 200, 5.00, 2),
('Clous', 2, 5000, 0.10, 2),
('Fonds pour école', 3, 100000, 1.00, 3),
('Médicaments', 1, 200, 3.50, 4),
('Ciment', 2, 1500, 0.80, 5);

INSERT INTO besoin(libelle_besoin, id_type, quantite, prix_unitaire, id_ville) VALUES 
('Preservatif' , 1 , 100 , 300 , 3);

-- =========================================
-- TABLE DON
-- =========================================
CREATE TABLE don(
    id_don INT PRIMARY KEY AUTO_INCREMENT,
    libelle_don VARCHAR(255) NOT NULL,
    id_type INT NOT NULL,
    quantite INT NOT NULL,  
    date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(id_type) REFERENCES type(id_type)
);

INSERT INTO don (libelle_don, id_type, quantite, date_don) VALUES
('Don de riz - Association X', 1, 500, '2026-02-10'),
('Don de tôles - Entreprise Y', 2, 100, '2026-02-12'),
('Don financier - Privé', 3, 50000, '2026-02-14'),
('Don huile - ONG Z', 1, 300, '2026-02-15'),
('Don de clous - Magasin', 2, 3000, '2026-02-13'),
('Don financier - Collecte', 3, 75000, '2026-02-11'),
('Don de ciment - Fournisseur', 2, 800, '2026-02-14');
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
CREATE TABLE achat (
    id_achat INT PRIMARY KEY AUTO_INCREMENT,
    id_besoin INT,
    id_don INT,
    quantite INT,
    montant DECIMAL(10,2),
    frais DECIMAL(10,2),
    montant_total DECIMAL(10,2),
    date_achat DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_besoin) REFERENCES besoin(id_besoin),
    FOREIGN KEY (id_don) REFERENCES don(id_don)
);

CREATE TABLE config(
    cle_config VARCHAR(50) PRIMARY KEY,
    valeur_config DECIMAL(5,2) NOT NULL
);

INSERT INTO config (cle_config, valeur_config) VALUES ('frais_achat', 10.00);
-- =========================================
-- VIEW VILLE_BESOIN
-- =========================================
CREATE OR REPLACE VIEW v_villeBesoin AS
SELECT
    v.id_ville,
    v.nom_ville,
    b.id_besoin,
    b.libelle_besoin,
    t.nom_type,
    b.quantite,
    b.prix_unitaire,
    (b.quantite * b.prix_unitaire) AS montant_total
FROM ville v
JOIN besoin b ON b.id_ville = v.id_ville
JOIN type t ON t.id_type = b.id_type;




