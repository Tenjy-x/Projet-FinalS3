-- =========================================
-- INITIALISATION DE LA CONFIGURATION DES FRAIS
-- =========================================

-- Insérer la configuration des frais par défaut (10%)
INSERT INTO config (cle_config, valeur_config) 
VALUES ('frais_achat', 10.00)
ON DUPLICATE KEY UPDATE cle_config = cle_config;

-- Vérifier que la configuration a été insérée
SELECT * FROM config WHERE cle_config = 'frais_achat';
