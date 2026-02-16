# Guide de Test des Fonctionnalités d'Achat

## 📋 Étapes d'Installation

### 1. Créer les vues SQL
```bash
mysql -u root -p BNGRC < base/sujet1_16/02/V3_views.sql
```

### 2. Initialiser la configuration
```bash
mysql -u root -p BNGRC < base/sujet1_16/02/V4_init_achats.sql
```

## 🔗 Pages de Test Disponibles

### 🏠 Page d'Accueil
**URL:** `/`
- Nouvelle section "Accès Rapide" avec 4 cartes
- Liens directs vers toutes les fonctionnalités

### 🛒 Page des Achats (Besoins Restants)
**URL:** `/besoins-restants`

**Fonctionnalités:**
- ✅ Liste des besoins restants filtrables par ville
- ✅ Affichage des dons en argent disponibles
- ✅ Bouton "Acheter" sur chaque besoin
- ✅ Modal de simulation avant achat
- ✅ Calcul automatique avec frais
- ✅ Validation sécurisée avec transaction

**Test à effectuer:**
1. Cliquer sur "Acheter" pour un besoin
2. Sélectionner un don en argent
3. Entrer une quantité
4. Cliquer sur "Simuler" → Voir le calcul avec frais
5. Cliquer sur "Valider l'Achat" → Enregistrement en base

### 📜 Page Historique des Achats
**URL:** `/achats`

**Fonctionnalités:**
- ✅ Liste complète des achats effectués
- ✅ Filtre par ville
- ✅ Statistiques (montant total, nombre d'achats)
- ✅ Détails: don utilisé, frais, montant total

### 📊 Page Récapitulatif Global
**URL:** `/recap`

**Fonctionnalités:**
- ✅ Montant total des besoins
- ✅ Montant satisfait (attributions + achats)
- ✅ Montant restant
- ✅ Barre de progression animée
- ✅ Bouton "Actualiser" (Ajax) ⚡
- ✅ Auto-actualisation toutes les 10 secondes

**Test à effectuer:**
1. Observer les statistiques actuelles
2. Cliquer sur "Actualiser" → Mise à jour sans recharger la page
3. Faire un achat sur `/besoins-restants`
4. Revenir sur `/recap` et cliquer "Actualiser"
5. Observer les changements en temps réel

## 🎯 API Endpoints (Pour tests avancés)

### POST `/api/achat/simuler`
**Body JSON:**
```json
{
  "id_don": 3,
  "id_besoin": 1,
  "quantite": 10
}
```

**Réponse:**
```json
{
  "success": true,
  "simulation": {
    "besoin": "Riz",
    "ville": "Antananarivo",
    "type": "nature",
    "quantite": 10,
    "prix_unitaire": 1.50,
    "montant_base": 15.00,
    "frais": 1.50,
    "pourcentage_frais": 10,
    "montant_total": 16.50,
    "montant_restant_don_apres": 49983.50
  }
}
```

### POST `/api/achat/valider`
**Body JSON:**
```json
{
  "id_don": 3,
  "id_besoin": 1,
  "quantite": 10
}
```

### GET `/api/recap`
**Réponse:**
```json
{
  "success": true,
  "recap": {
    "montant_total": "105200.00",
    "montant_satisfait": "52000.00",
    "montant_restant": "53200.00",
    "pourcentage_satisfait": 49.43
  }
}
```

## 📝 Scénario de Test Complet

1. **Démarrer le serveur**
   ```bash
   php -S localhost:8080 -t public
   ```

2. **Accéder à la page d'accueil**
   - Ouvrir: `http://localhost:8080/`
   - Vérifier que les 4 nouvelles cartes s'affichent

3. **Tester la simulation d'achat**
   - Cliquer sur "Achats" → `/besoins-restants`
   - Cliquer sur "Acheter" pour le Riz (par exemple)
   - Sélectionner "Don financier - Privé"
   - Quantité: 10
   - Cliquer "Simuler"
   - ✅ Vérifier le calcul avec frais (10%)

4. **Valider un achat**
   - Après simulation réussie
   - Cliquer "Valider l'Achat"
   - ✅ Vérifier le message de succès
   - ✅ Page rechargée avec quantités mises à jour

5. **Vérifier l'historique**
   - Aller sur `/achats`
   - ✅ Voir le nouvel achat dans la liste
   - Tester le filtre par ville

6. **Consulter le récapitulatif**
   - Aller sur `/recap`
   - ✅ Observer les montants et le pourcentage
   - Cliquer "Actualiser"
   - ✅ Notification de succès

## ⚠️ Cas d'Erreur à Tester

1. **Quantité supérieure au reste**
   - Essayer d'acheter plus que disponible
   - ✅ Message: "Quantité demandée supérieure au restant"

2. **Montant insuffisant**
   - Choisir un don avec peu d'argent
   - Acheter un besoin coûteux
   - ✅ Message: "Montant insuffisant. Nécessaire: X, Disponible: Y"

3. **Achat sans simulation**
   - Essayer de valider sans simuler
   - ✅ Bouton "Valider" désactivé jusqu'à simulation

## 🎨 Fonctionnalités Visuelles

- ✨ Interface Bootstrap 5 moderne
- 📱 Responsive (mobile-friendly)
- 🎭 Animations CSS (barre de progression)
- ⚡ AJAX pour actualisation sans rechargement
- 🔔 Notifications toast temporaires
- 🎨 Badges colorés par type (nature/matériaux)
- 📊 Tableaux avec totaux
- 🔄 Loader pendant chargement

## 🚀 Prochaines Étapes

Une fois les tests effectués avec succès:
- [ ] Vérifier tous les liens fonctionnent
- [ ] Tester les filtres par ville
- [ ] Valider les calculs de frais
- [ ] S'assurer des rollbacks en cas d'erreur
- [ ] Tester avec plusieurs achats simultanés
