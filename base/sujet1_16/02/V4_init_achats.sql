-- Script d'initialisation pour les fonctionnalités d'achat
-- À exécuter après V2.sql et V3_views.sql

USE BNGRC;

-- Insertion de la configuration des frais (10% par défaut)
INSERT INTO config (cle_config, valeur_config) VALUES ('frais_achat', 10.00)
ON DUPLICATE KEY UPDATE valeur_config = 10.00;

-- Vérification et affichage
SELECT 'Configuration des frais d\'achat' AS Info, valeur_config AS 'Frais (%)' 
FROM config WHERE cle_config = 'frais_achat';

-- Afficher les besoins restants
SELECT 'Besoins restants' AS Info;
SELECT * FROM v_besoins_restants WHERE reste > 0 LIMIT 5;

-- Afficher les dons en argent disponibles
SELECT 'Dons en argent disponibles' AS Info;
SELECT * FROM v_dons_argent_restants WHERE reste_argent > 0 LIMIT 5;

-- Afficher le récapitulatif global
SELECT 'Récapitulatif global' AS Info;
SELECT * FROM v_recap_global;

SELECT '✅ Initialisation terminée avec succès!' AS Status;
