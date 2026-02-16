#!/bin/bash

# Script de démarrage rapide pour tester les fonctionnalités d'achat

echo "🚀 Installation et Démarrage - BNGRC Achats"
echo "=========================================="
echo ""

# Vérifier si MySQL est accessible
echo "1️⃣  Vérification de MySQL..."
if ! command -v mysql &> /dev/null; then
    echo "❌ MySQL n'est pas installé ou pas dans le PATH"
    exit 1
fi
echo "✅ MySQL OK"
echo ""

# Demander les credentials MySQL
echo "2️⃣  Configuration de la base de données"
read -p "Utilisateur MySQL (par défaut: root): " MYSQL_USER
MYSQL_USER=${MYSQL_USER:-root}
read -sp "Mot de passe MySQL: " MYSQL_PASS
echo ""
echo ""

# Créer les vues SQL
echo "3️⃣  Création des vues SQL..."
mysql -u "$MYSQL_USER" -p"$MYSQL_PASS" BNGRC < base/sujet1_16/02/V3_views.sql
if [ $? -eq 0 ]; then
    echo "✅ Vues créées avec succès"
else
    echo "❌ Erreur lors de la création des vues"
    exit 1
fi
echo ""

# Initialiser la configuration
echo "4️⃣  Initialisation de la configuration (frais 10%)..."
mysql -u "$MYSQL_USER" -p"$MYSQL_PASS" BNGRC < base/sujet1_16/02/V4_init_achats.sql
if [ $? -eq 0 ]; then
    echo "✅ Configuration initialisée"
else
    echo "❌ Erreur lors de l'initialisation"
    exit 1
fi
echo ""

# Démarrer le serveur PHP
echo "5️⃣  Démarrage du serveur PHP..."
echo "📡 Serveur disponible sur: http://localhost:8080"
echo ""
echo "📌 Pages de test disponibles:"
echo "   - http://localhost:8080/                 (Accueil avec liens rapides)"
echo "   - http://localhost:8080/besoins-restants (Effectuer des achats)"
echo "   - http://localhost:8080/achats           (Historique des achats)"
echo "   - http://localhost:8080/recap            (Récapitulatif global)"
echo ""
echo "🛑 Appuyez sur Ctrl+C pour arrêter le serveur"
echo ""

cd public
php -S localhost:8080
