<?php


declare(strict_types=1);

/**
 * Point d'entrée principal de l'application
 * 
 * Ce fichier gère le routage des requêtes et initialise l'application.
 * 
 * @author Votre Nom
 * @version 1.0
 */

// Démarrage de la session
session_start();

use App\Controller\LivreController;
use App\Controller\UtilisateurController;
use Dotenv\Dotenv;

// Chargement des variables d'environnement
$dotenv = Dotenv::createMutable(__DIR__);
$dotenv->load();

// Inclusion des fichiers d'initialisation et de fonctions
require __DIR__ . '/app/lib/init.php';
require __DIR__ . '/app/lib/functions.php';

// Initialisation des contrôleurs
$livreController = new LivreController();
$utilisateurController = new UtilisateurController();

try {
    // Routage des requêtes
    if (empty($_GET['page'])) {
        // Page d'accueil : affichage de tous les livres
        $livreController->getAllLivres();
    } else {
        // Analyse de l'URL pour déterminer l'action à effectuer
        $url = explode("/", filter_var($_GET['page'], FILTER_SANITIZE_URL));

        switch ($url[0]) {
            case 'livres':
                // Gestion des actions liées aux livres
                if (empty($url[1])) {
                    $livreController->afficherLivres();
                } elseif ($url[1] === 'l') {
                    $livreController->afficherUnLivre((int)$url[2]);
                } elseif ($url[1] === 'a') {
                    $livreController->ajouterLivre();
                } elseif ($url[1] === 'av') {
                    $livreController->validationAjoutLivre();
                } elseif ($url[1] === 'm') {
                    $livreController->modifierLivre((int)$url[2]);
                } elseif ($url[1] === 'mv') {
                    $livreController->validationModifierLivre();
                } elseif ($url[1] === 's') {
                    $livreController->supprimerLivre((int)$url[2]);
                } else {
                    throw new Exception("La page n'existe pas");
                }
                break;

            case 'connexion':
                // Gestion de la connexion utilisateur
                if (empty($url[1])) {
                    $utilisateurController->afficherConnexion();
                } elseif ($url[1] === 'v') {
                    $utilisateurController->connexionValidation();
                }
                break;

            case 'profil':
                // Gestion du profil utilisateur
                if (empty($url[1])) {
                    $utilisateurController->afficherProfil();
                } elseif ($url[1] === 'm') {
                    $utilisateurController->modificationProfil((int)$url[2]);
                }
                break;

            case 'inscription':
                // Gestion de l'inscription utilisateur
                if (empty($url[1])) {
                    $utilisateurController->afficherInscription();
                } elseif ($url[1] === 'v') {
                    $utilisateurController->inscriptionValidation();
                }
                break;

            case 'verification-email':
                // Vérification de l'email après inscription
                $utilisateurController->verifierEmail($url[1]);
                break;

            case 'gestion-membres':
                // Gestion des membres (administration)
                if (empty($url[1])) {
                    $utilisateurController->afficherGestionMembres();
                } elseif ($url[1] === 'm') {
                    $utilisateurController->modifierUtilisateurByAdmin((int)$url[2]);
                } elseif ($url[1] === 's') {
                    $utilisateurController->supprimerUtilisateurByAdmin((int)$url[2]);
                }
                break;

            case 'deconnexion':
                // Déconnexion de l'utilisateur
                $utilisateurController->logout();
                break;

            default:
                // Page non trouvée
                throw new Exception("La page n'existe pas");
                break;
        }
    }
} catch (Exception $e) {
    // Gestion des erreurs : affichage de la page d'erreur 404
    $message = $e->getMessage();
    include '../app/Views/error404.php';
}
