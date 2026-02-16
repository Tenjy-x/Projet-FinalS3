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
    id_ville INT,
    FOREIGN KEY(id_ville) REFERENCES ville(id_ville)
);
INSERT INTO besoin (libelle_besoin, type_besoin, quantite, prix_unitaire, id_ville) VALUES
('Riz', 'nature', 1000, 1.50, 1),
('Huile', 'nature', 500, 2.00, 1),
('Tôles', 'materiaux', 200, 5.00, 2),
('Clous', 'materiaux', 5000, 0.10, 2),
('Fonds pour école', 'argent', 100000, 1.00, 3),
('Médicaments', 'nature', 200, 3.50, 4),
('Ciment', 'materiaux', 1500, 0.80, 5);

-- =========================================
-- TABLE DON
-- =========================================
CREATE TABLE don(
    id_don INT PRIMARY KEY AUTO_INCREMENT,
    libelle_don VARCHAR(255) NOT NULL,
    type_don ENUM('nature','materiaux','argent') NOT NULL,
    quantite INT NOT NULL,  
    date_don DATE NOT NULL
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
    date_attribution DATE NOT NULL,
    FOREIGN KEY(id_don) REFERENCES don(id_don),
    FOREIGN KEY(id_besoin) REFERENCES besoin(id_besoin)
);




