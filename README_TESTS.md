# Guide de Test - Système d'Achat BNGRC

## 📋 Prérequis

### 1. Initialiser la base de données

```bash
# Créer les vues SQL
mysql -u root -p BNGRC < base/sujet1_16/02/V3_views.sql

# Initialiser la configuration des frais (10% par défaut)
mysql -u root -p BNGRC < base/sujet1_16/02/V4_config_frais.sql
```

### 2. Démarrer le serveur

```bash
php -S localhost:8000 -t public
```

## 🌐 Pages Web à Tester

### 📱 Menu de Test Principal
**URL:** http://localhost:8000/menu-test

Page principale avec tous les liens de test et exemples d'API.

### 🛒 Page des Achats (Besoins Restants)
**URL:** http://localhost:8000/besoins-restants

**Fonctionnalités:**
- Voir tous les besoins non satisfaits
- Filtrer par ville
- Voir les dons en argent disponibles
- Simuler un achat
- Valider un achat
- Affiche le % de frais configuré avec lien vers la configuration

**Comment tester:**
1. Sélectionner un besoin
2. Choisir un don en argent
3. Entrer la quantité
4. Cliquer sur "Simuler" pour voir le calcul avec frais
5. Cliquer sur "Valider" pour enregistrer l'achat

### 📜 Historique des Achats
**URL:** http://localhost:8000/achats

**Fonctionnalités:**
- Liste de tous les achats effectués
- Filtrage par ville
- Affichage des détails (montant, frais, total)

### 📊 Récapitulatif Global
**URL:** http://localhost:8000/recap

**Fonctionnalités:**
- Montant total des besoins
- Montant satisfait
- Montant restant
- Pourcentage de satisfaction
- Bouton "Actualiser" en Ajax (sans rechargement de page)

### ⚙️ Configuration des Frais
**URL:** http://localhost:8000/config-frais

**Fonctionnalités:**
- Voir le pourcentage de frais actuel
- Modifier le pourcentage (0-100%)
- Exemple de calcul en temps réel
- Application automatique aux futurs achats

## 🔌 API REST (JSON)

### 1. Simuler un Achat
**POST** `/api/achat/simuler`

```json
{
  "id_don": 1,
  "id_besoin": 2,
  "quantite": 100
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
    "quantite": 100,
    "prix_unitaire": 1.50,
    "montant_base": 150.00,
    "frais": 15.00,
    "pourcentage_frais": 10,
    "montant_total": 165.00,
    "montant_restant_don_apres": 334.00
  }
}
```

### 2. Valider un Achat
**POST** `/api/achat/valider`

```json
{
  "id_don": 1,
  "id_besoin": 2,
  "quantite": 100
}
```

**Réponse:**
```json
{
  "success": true,
  "message": "Achat validé avec succès",
  "achat": {
    "besoin": "Riz",
    "ville": "Antananarivo",
    "quantite": 100,
    "montant_total": 165.00
  }
}
```

### 3. Récapitulatif Global
**GET** `/api/recap`

**Réponse:**
```json
{
  "success": true,
  "recap": {
    "montant_total": "10500.00",
    "montant_satisfait": "5250.00",
    "montant_restant": "5250.00",
    "pourcentage_satisfait": 50
  }
}
```

## 🧪 Tests avec curl

### Simuler un achat
```bash
curl -X POST http://localhost:8000/api/achat/simuler \
  -H "Content-Type: application/json" \
  -d '{"id_don":1,"id_besoin":2,"quantite":100}'
```

### Valider un achat
```bash
curl -X POST http://localhost:8000/api/achat/valider \
  -H "Content-Type: application/json" \
  -d '{"id_don":1,"id_besoin":2,"quantite":100}'
```

### Récupérer le récapitulatif
```bash
curl http://localhost:8000/api/recap
```

## 🧪 Tests JavaScript (Console du navigateur)

```javascript
// Simuler un achat
fetch('/api/achat/simuler', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        id_don: 1,
        id_besoin: 2,
        quantite: 100
    })
})
.then(res => res.json())
.then(data => console.log(data));

// Valider un achat
fetch('/api/achat/valider', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        id_don: 1,
        id_besoin: 2,
        quantite: 100
    })
})
.then(res => res.json())
.then(data => console.log(data));

// Récupérer le récapitulatif
fetch('/api/recap')
    .then(res => res.json())
    .then(data => console.log(data));
```

## ✅ Scénario de Test Complet

### Étape 1: Configuration
1. Aller sur http://localhost:8000/config-frais
2. Définir les frais à 10%
3. Enregistrer

### Étape 2: Consulter les besoins
1. Aller sur http://localhost:8000/besoins-restants
2. Observer les besoins restants
3. Observer les dons en argent disponibles

### Étape 3: Simuler un achat
1. Sélectionner un besoin en nature ou matériaux
2. Sélectionner un don en argent
3. Entrer une quantité
4. Cliquer sur "Simuler l'achat"
5. Vérifier le calcul des frais (10%)

### Étape 4: Valider l'achat
1. Si la simulation est correcte
2. Cliquer sur "Valider l'achat"
3. Vérifier le message de succès

### Étape 5: Consulter l'historique
1. Aller sur http://localhost:8000/achats
2. Retrouver l'achat effectué
3. Vérifier les montants

### Étape 6: Voir le récapitulatif
1. Aller sur http://localhost:8000/recap
2. Observer les statistiques
3. Cliquer sur "Actualiser" (Ajax)
4. Vérifier la mise à jour

## 📌 Points Importants

### Validation automatique
- ✅ Vérification que le besoin n'est pas déjà satisfait
- ✅ Vérification du montant disponible dans le don
- ✅ Calcul automatique des frais
- ✅ Déduction automatique du montant du don
- ✅ Enregistrement dans la table `achat`

### Frais configurables
- Le pourcentage est stocké dans la table `config`
- Modifiable à tout moment via `/config-frais`
- Appliqué automatiquement à tous les achats
- Affiché sur toutes les pages concernées

### Filtrage
- Les besoins restants peuvent être filtrés par ville
- Les achats peuvent être filtrés par ville
- Les filtres sont dans l'URL (bookmarkable)

## 🎯 Navigation Complète

Toutes les pages ont une navigation cohérente avec:
- Accueil
- Achats (besoins restants)
- Historique
- Récapitulatif
- Configuration (avec icône engrenage)

## 📊 Vues SQL Créées

1. **v_besoins_restants** - Besoins avec calculs de quantités restantes
2. **v_dons_argent_restants** - Dons en argent avec montants disponibles
3. **v_recap_global** - Récapitulatif des montants globaux
4. **v_achats_details** - Détails complets des achats

Ces vues simplifient grandement les requêtes et améliorent les performances.
