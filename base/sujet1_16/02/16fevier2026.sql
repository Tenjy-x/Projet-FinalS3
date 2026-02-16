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
