<?php
namespace app\controllers;

use app\models\DispatchModel;
use app\models\Allmodels;
use flight\Engine;
use Flight;

class DispatchController
{
    protected Engine $app;

    public function __construct(Engine $app)
    {
        $this->app = $app;
    }

    public function dispatch()
    {
        $model = new DispatchModel($this->app->db());
        $result = $model->dispatchDons();

        // $this->app->json([
        //     'status' => 'ok',
        //     'attributions' => $result['attributions'],
        //     'quantite' => $result['quantite']
        // ]);

        return $result;
    }

    /**
     * Dispatch par quantité : les plus petites quantités sont attribuées en premier
     */
    public function dispatchParQuantite()
    {
        $model = new DispatchModel($this->app->db());
        $result = $model->dispatchParQuantite();
        return $result;
    }

    /**
     * Réinitialise les données (ville, besoin, don, attribution) depuis v1.sql
     */
    public function resetData()
    {
        $db = $this->app->db();
        $sqlPath = __DIR__ . '/../../base/sujet1_16/02/v1.sql';

        if (!file_exists($sqlPath)) {
            $this->app->halt(500, 'Fichier v1.sql introuvable');
        }

        $content = file_get_contents($sqlPath);
        if ($content === false) {
            $this->app->halt(500, 'Impossible de lire v1.sql');
        }

        // Supprimer les lignes commentées (-- ...) avant d'extraire les INSERT
        $cleanContent = preg_replace('/^\s*--.*$/m', '', $content);

        $tables = ['type', 'produit', 'ville', 'besoin', 'don', 'config'];
        $insertStatements = [];
        foreach ($tables as $table) {
            $insertStatements[$table] = [];
            if (preg_match_all('/^[^-\n]*INSERT\s+INTO\s+' . $table . '\b.*?;/ims', $content, $matches)) {
                $insertStatements[$table] = $matches[0];
            }
        }

        $hasInserts = false;
        foreach ($insertStatements as $stmts) {
            if (!empty($stmts)) {
                $hasInserts = true;
                break;
            }
        }
        if (!$hasInserts) {
            $this->app->halt(500, 'Aucune insertion trouvée dans v1.sql');
        }

        try {
            $db->exec('SET FOREIGN_KEY_CHECKS=0');

            // Supprimer toutes les données
            $db->exec('DELETE FROM achat');
            $db->exec('DELETE FROM attribution');
            $db->exec('DELETE FROM don');
            $db->exec('DELETE FROM besoin');
            $db->exec('DELETE FROM ville');
            $db->exec('DELETE FROM produit');
            $db->exec('DELETE FROM type');
            $db->exec('DELETE FROM config');

            // Réinitialiser les auto-incréments (ignorer si pas autorisé)
            $resetTables = ['achat', 'attribution', 'don', 'besoin', 'ville', 'produit', 'type'];
            foreach ($resetTables as $t) {
                try {
                    $db->exec("ALTER TABLE {$t} AUTO_INCREMENT = 1");
                } catch (\Throwable $ignore) {
                    // Certains serveurs n'autorisent pas ALTER TABLE
                }
            }

            // Réinsérer dans l'ordre (type → produit → ville → besoin → don → config)
            foreach ($tables as $table) {
                foreach ($insertStatements[$table] as $stmt) {
                    $db->exec($stmt);
                }
            }

            $db->exec('SET FOREIGN_KEY_CHECKS=1');
        } catch (\Throwable $e) {
            $db->exec('SET FOREIGN_KEY_CHECKS=1');
            $this->app->halt(500, 'Erreur reset: ' . $e->getMessage());
        }
    }

    /**
     * Calcule le montant total avec frais d'achat
     * @param float $montant Montant de base
     * @return array ['montant' => montant de base, 'frais' => frais, 'total' => montant total]
     */
    public function calculerMontantTotal($montant)
    {
        $model = new Allmodels($this->app->db());
        $pourcentageFrais = $model->getFraisConfig();
        
        $frais = ($montant * $pourcentageFrais) / 100;
        $total = $montant + $frais;
        
        return [
            'montant' => (float) $montant,
            'frais' => (float) $frais,
            'total' => (float) $total,
            'pourcentage_frais' => (float) $pourcentageFrais
        ];
    }

    /**
     * Simule un achat sans modifications en base de données
     * @return void Retourne JSON
     */
    public function simulerAchat()
    {
        try {
            $idDon = (int) ($this->app->request()->data->id_don ?? 0);
            $idBesoin = (int) ($this->app->request()->data->id_besoin ?? 0);
            $quantite = (int) ($this->app->request()->data->quantite ?? 0);

            // Validation
            if ($idDon <= 0 || $idBesoin <= 0 || $quantite <= 0) {
                $this->app->json([
                    'success' => false,
                    'error' => 'Données invalides'
                ], 400);
                return;
            }

            $model = new Allmodels($this->app->db());
            
            // Vérifier le besoin
            $besoins = $model->getBesoinsRestants();
            $besoin = null;
            foreach ($besoins as $b) {
                if ((int) $b['id_besoin'] === $idBesoin) {
                    $besoin = $b;
                    break;
                }
            }

            if (!$besoin) {
                $this->app->json([
                    'success' => false,
                    'error' => 'Besoin introuvable ou déjà satisfait'
                ], 404);
                return;
            }

            // Vérifier que la quantité demandée ne dépasse pas le reste
            if ($quantite > (int) $besoin['reste']) {
                $this->app->json([
                    'success' => false,
                    'error' => "Quantité demandée ({$quantite}) supérieure au restant ({$besoin['reste']})"
                ], 400);
                return;
            }

            // Vérifier le type de besoin (ne peut pas être argent)
            if ($besoin['nom_type'] === 'argent') {
                $this->app->json([
                    'success' => false,
                    'error' => 'Les besoins en argent ne peuvent pas être achetés'
                ], 400);
                return;
            }

            // Calculer le montant nécessaire
            $prixUnitaire = (float) $besoin['prix_unitaire'];
            $montantBase = $quantite * $prixUnitaire;
            $calcul = $this->calculerMontantTotal($montantBase);

            // Vérifier le don en argent
            $montantRestant = $model->getMontantRestantDon($idDon);
            if ($montantRestant <= 0) {
                $this->app->json([
                    'success' => false,
                    'error' => 'Don introuvable ou sans argent restant'
                ], 404);
                return;
            }

            if ($calcul['total'] > $montantRestant) {
                $this->app->json([
                    'success' => false,
                    'error' => "Montant insuffisant. Nécessaire: {$calcul['total']} Ar, Disponible: {$montantRestant} Ar"
                ], 400);
                return;
            }

            // Simulation réussie
            $this->app->json([
                'success' => true,
                'simulation' => [
                    'besoin' => $besoin['nom_produit'],
                    'ville' => $besoin['nom_ville'],
                    'type' => $besoin['nom_type'],
                    'quantite' => $quantite,
                    'prix_unitaire' => $prixUnitaire,
                    'montant_base' => $calcul['montant'],
                    'frais' => $calcul['frais'],
                    'pourcentage_frais' => $calcul['pourcentage_frais'],
                    'montant_total' => $calcul['total'],
                    'montant_restant_don_apres' => $montantRestant - $calcul['total']
                ]
            ]);

        } catch (\Exception $e) {
            $this->app->json([
                'success' => false,
                'error' => 'Erreur lors de la simulation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Valide et enregistre un achat en base de données avec transaction
     * @return void Retourne JSON
     */
    public function validerAchat()
    {
        $db = $this->app->db();
        
        try {
            $db->beginTransaction();

            $idDon = (int) ($this->app->request()->data->id_don ?? 0);
            $idBesoin = (int) ($this->app->request()->data->id_besoin ?? 0);
            $quantite = (int) ($this->app->request()->data->quantite ?? 0);

            // Validation
            if ($idDon <= 0 || $idBesoin <= 0 || $quantite <= 0) {
                $db->rollBack();
                $this->app->json([
                    'success' => false,
                    'error' => 'Données invalides'
                ], 400);
                return;
            }

            $model = new Allmodels($db);
            
            // Vérifier le besoin
            $besoins = $model->getBesoinsRestants();
            $besoin = null;
            foreach ($besoins as $b) {
                if ((int) $b['id_besoin'] === $idBesoin) {
                    $besoin = $b;
                    break;
                }
            }

            if (!$besoin) {
                $db->rollBack();
                $this->app->json([
                    'success' => false,
                    'error' => 'Besoin introuvable ou déjà satisfait'
                ], 404);
                return;
            }

            // Vérifier que la quantité demandée ne dépasse pas le reste
            if ($quantite > (int) $besoin['reste']) {
                $db->rollBack();
                $this->app->json([
                    'success' => false,
                    'error' => "Quantité demandée ({$quantite}) supérieure au restant ({$besoin['reste']})"
                ], 400);
                return;
            }

            // Vérifier le type de besoin (ne peut pas être argent)
            if ($besoin['nom_type'] === 'argent') {
                $db->rollBack();
                $this->app->json([
                    'success' => false,
                    'error' => 'Les besoins en argent ne peuvent pas être achetés'
                ], 400);
                return;
            }

            // Calculer le montant nécessaire
            $prixUnitaire = (float) $besoin['prix_unitaire'];
            $montantBase = $quantite * $prixUnitaire;
            $calcul = $this->calculerMontantTotal($montantBase);

            // Vérifier le montant suffisant
            if (!$model->verifierMontantSuffisant($idDon, $calcul['total'])) {
                $montantRestant = $model->getMontantRestantDon($idDon);
                $db->rollBack();
                $this->app->json([
                    'success' => false,
                    'error' => "Montant insuffisant. Nécessaire: {$calcul['total']} Ar, Disponible: {$montantRestant} Ar"
                ], 400);
                return;
            }

            // Enregistrer l'achat
            $success = $model->createAchat(
                $idDon,
                $idBesoin,
                $quantite,
                $calcul['montant'],
                $calcul['frais'],
                $calcul['total']
            );

            if (!$success) {
                $db->rollBack();
                $this->app->json([
                    'success' => false,
                    'error' => 'Erreur lors de l\'enregistrement de l\'achat'
                ], 500);
                return;
            }

            $db->commit();

            $this->app->json([
                'success' => true,
                'message' => 'Achat validé avec succès',
                'achat' => [
                    'besoin' => $besoin['nom_produit'],
                    'ville' => $besoin['nom_ville'],
                    'quantite' => $quantite,
                    'montant_total' => $calcul['total']
                ]
            ]);

        } catch (\Exception $e) {
            $db->rollBack();
            $this->app->json([
                'success' => false,
                'error' => 'Erreur lors de la validation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère le récapitulatif global des besoins (pour affichage Ajax)
     * @return void Retourne JSON
     */
    public function getRecapGlobal()
    {
        try {
            $model = new Allmodels($this->app->db());
            $recap = $model->getTotalSatisfait();

            $this->app->json([
                'success' => true,
                'recap' => [
                    'montant_total' => number_format($recap['montant_total'], 2, '.', ''),
                    'montant_satisfait' => number_format($recap['montant_satisfait'], 2, '.', ''),
                    'montant_restant' => number_format($recap['montant_restant'], 2, '.', ''),
                    'pourcentage_satisfait' => $recap['montant_total'] > 0 
                        ? round(($recap['montant_satisfait'] / $recap['montant_total']) * 100, 2) 
                        : 0
                ]
            ]);

        } catch (\Exception $e) {
            $this->app->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération du récapitulatif: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche la page des besoins restants avec possibilité d'achat
     * @return void
     */
    public function pageBesoinsRestants()
    {
        $model = new Allmodels($this->app->db());
        
        $idVille = $this->app->request()->query->id_ville ?? null;
        $idVille = ($idVille !== '' && $idVille !== null) ? (int) $idVille : null;
        $besoins = $model->getBesoinsRestants($idVille);
        $donsArgent = $model->getDonsArgentRestants();
        $villes = $model->getAllVilles();
        $frais = $model->getFraisConfig();

        $this->app->render('Modal', [
            'page' => 'besoins_restants',
            'besoins' => $besoins,
            'donsArgent' => $donsArgent,
            'villes' => $villes,
            'frais' => $frais,
            'id_ville_selectionnee' => $idVille
        ]);
    }

    /**
     * Affiche la page de récapitulation
     * @return void
     */
    public function pageRecap()
    {
        $model = new Allmodels($this->app->db());
        $recap = $model->getTotalSatisfait();

        $this->app->render('Modal', [
            'page' => 'recap',
            'recap' => $recap
        ]);
    }

    /**
     * Affiche la liste des achats filtrables par ville
     * @return void
     */
    public function pageAchats()
    {
        $model = new Allmodels($this->app->db());
        
        $idVille = $this->app->request()->query->id_ville ?? null;
        $idVille = ($idVille !== '' && $idVille !== null) ? (int) $idVille : null;
        $achats = $model->getAchatsDetails($idVille);
        $villes = $model->getAllVilles();

        $this->app->render('Modal', [
            'page' => 'achats',
            'achats' => $achats,
            'villes' => $villes,
            'id_ville_selectionnee' => $idVille
        ]);
    }

    /**
     * Affiche et gère la page de configuration des frais
     * @return void
     */
    public function pageConfigFrais()
    {
        $model = new Allmodels($this->app->db());
        
        // Traitement du formulaire POST
        if ($this->app->request()->method === 'POST') {
            try {
                $frais = (float) ($this->app->request()->data->frais ?? 0);
                
                // Validation
                if ($frais < 0 || $frais > 100) {
                    $fraisActuel = $model->getFraisConfig();
                    $this->app->render('config_frais', [
                        'frais' => $fraisActuel,
                        'error' => 'Les frais doivent être entre 0 et 100%'
                    ]);
                    return;
                }
                
                // Enregistrement de la configuration
                $success = $model->setFraisConfig($frais);
                
                if ($success) {
                    $this->app->render('config_frais', [
                        'frais' => $frais,
                        'success' => true
                    ]);
                } else {
                    $this->app->render('config_frais', [
                        'frais' => $frais,
                        'error' => 'Erreur lors de l\'enregistrement de la configuration'
                    ]);
                }
            } catch (\Exception $e) {
                $fraisActuel = $model->getFraisConfig();
                $this->app->render('config_frais', [
                    'frais' => $fraisActuel,
                    'error' => 'Erreur: ' . $e->getMessage()
                ]);
            }
            return;
        }
        
        // Affichage du formulaire GET
        $frais = $model->getFraisConfig();
        $this->app->render('config_frais', [
            'frais' => $frais
        ]);
    }
}