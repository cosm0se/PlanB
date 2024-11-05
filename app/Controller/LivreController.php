<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Utils;
use App\Service\ValidationDonnees;
use App\Repository\LivresRepository;
use App\Controller\UtilisateurController;
use App\Service\Csrf;

/**
 * Contrôleur pour la gestion des livres
 */
class LivreController
{
    private LivresRepository $repositoryLivres;
    private ValidationDonnees $validationDonnees;
    private UtilisateurController $utilisateurController;

    /**
     * Constructeur du contrôleur
     * Initialise les dépendances et charge les livres selon le rôle de l'utilisateur
     */
    public function __construct()
    {
        // Initialisation des dépendances
        $this->repositoryLivres = new LivresRepository();
        $this->validationDonnees = new ValidationDonnees();
        $this->utilisateurController = new UtilisateurController();

        // Vérification du rôle de l'utilisateur
        $isAdmin = $this->utilisateurController->isRoleAdmin();
        $isUser = $this->utilisateurController->isRoleUser();

        // Chargement des livres selon le rôle
        if ($isAdmin) {
            $this->repositoryLivres->chargementLivresBdd();
        } elseif ($isUser) {
            $this->repositoryLivres->getLivresByIdUtilisateur($_SESSION['utilisateur']['id_utilisateur']);
        }
    }

    /**
     * Affiche la liste des livres
     */
    public function afficherLivres()
    {
        $this->utilisateurController->redirectLogin();
        $livresTab = $this->repositoryLivres->getLivres();
        $pasDeLivre = (count($livresTab) > 0) ? false : true;

        require "../app/Views/livres.php";
    }

    /**
     * Affiche les détails d'un livre spécifique
     * 
     * @param int $idLivre L'ID du livre à afficher
     */
    public function afficherUnLivre($idLivre)
    {
        $livre = $this->repositoryLivres->getLivreById($idLivre);
        if ($livre !== null) {
            require "../app/Views/afficherlivre.php";
            exit;
        }
        $message = "Le livre avec l'ID : $idLivre n'existe pas";
        require "../app/Views/error404.php";
    }

    /**
     * Affiche le formulaire d'ajout d'un livre
     */
    public function ajouterLivre()
    {
        $this->utilisateurController->redirectLogin();
        $csrfToken = Csrf::token();
        require '../app/Views/ajouterLivre.php';
    }

    /**
     * Traite la soumission du formulaire d'ajout d'un livre
     */
    public function validationAjoutLivre()
    {
        Csrf::check();
        $this->utilisateurController->redirectLogin();
        unset($_SESSION['erreurs']);
        unset($_SESSION['old_values']);
        $erreurs = $this->validationDonnees->valider([
            'titre' => ['match:/^[A-Z][a-zA-Z0-9\- ]{3,50}$/'],
            'nbre-de-pages' => ['match:/^\d{1,10}$/'],
            'text-alternatif' => ['match:/^[a-zA-Z.\-\'\"\s]{10,150}$/']
        ], $_POST);

        if (is_array($erreurs) && count($erreurs) > 0) {
            $_SESSION['erreurs'] = $erreurs;
            $_SESSION['old_values'] = $_POST;
            header('location: ' . SITE_URL . 'livres/a');
            exit;
        }

        $image = $_FILES['image'];
        $repertoire = "images/";
        $nomImage = Utils::ajoutImage($image, $repertoire);

        if (isset($_SESSION['erreurs']) && count($_SESSION['erreurs']) > 0) {
            $_SESSION['old_values'] = $_POST;
            header('location: ' . SITE_URL . 'livres/a');
            exit;
        }

        $this->repositoryLivres->ajouterLivreBdd($_POST['titre'], (int)$_POST['nbre-de-pages'], $_POST['text-alternatif'], $nomImage, $_POST['description']);
        $_SESSION['alert'] = [
            "type" => "success",
            "message" => "Le livre $_POST[titre] a été ajouté avec succès!"
        ];
        header('location: ' . SITE_URL . 'livres');
    }

    /**
     * Affiche le formulaire de modification d'un livre
     * 
     * @param int $idLivre L'ID du livre à modifier
     */
    public function modifierLivre($idLivre)
    {
        $this->utilisateurController->redirectLogin();
        $livre = $this->repositoryLivres->getLivreById($idLivre);
        $csrfToken = Csrf::token();
        require '../app/Views/modifierLivre.php';
    }

    /**
     * Traite la soumission du formulaire de modification d'un livre
     */
    public function validationModifierLivre()
    {
        Csrf::check();
        unset($_SESSION['erreurs']);
        unset($_SESSION['old_values']);
        $erreurs = $this->validationDonnees->valider([
            'titre' => ['match:/^[A-Z][a-zA-Z\- ]{3,25}$/'],
            'nbre-de-pages' => ['match:/^\d{1,10}$/'],
            'text-alternatif' => ['match:/^[a-zA-Z.\-\'\"\s]{10,150}$/']
        ], $_POST);

        if (is_array($erreurs) && count($erreurs) > 0) {
            $_SESSION['erreurs'] = $erreurs;
            $_SESSION['old_values'] = $_POST;
            header('location: ' . SITE_URL . 'livres/m/' . (int)$_POST['id_livre']);
            exit;
        }

        $idLivre = (int)$_POST['id_livre'];
        $imageActuelle = $this->repositoryLivres->getLivreById($idLivre)->getUrlImage();
        $imageUpload = $_FILES['image'];
        $cheminImage = "images/$imageActuelle";
        if ($imageUpload['size'] > 0) {
            if (file_exists($cheminImage)) {
                unlink($cheminImage);
            }
            $imageActuelle = Utils::ajoutImage($imageUpload, "images/");
        }
        $this->repositoryLivres->modificationLivreBdd($_POST['titre'], (int)$_POST['nbre-de-pages'], $_POST['text-alternatif'], $_POST['description'], $imageActuelle, $idLivre);
        $_SESSION['alert'] = [
            "type" => "success",
            "message" => "Le livre $_POST[titre] a été modifié avec succès!"
        ];
        header('location: ' . SITE_URL . 'livres');
    }

    /**
     * Supprime un livre
     * 
     * @param int $idLivre L'ID du livre à supprimer
     */
    public function supprimerLivre($idLivre)
    {
        $this->utilisateurController->redirectLogin();
        $livre = $this->repositoryLivres->getLivreById($idLivre);
        $nomImage = $livre->getUrlImage();
        $filename = "images/$nomImage";
        if (file_exists($filename)) {
            unlink($filename);
        }
        $this->repositoryLivres->supprimerLivreBdd($idLivre);
        $_SESSION['alert'] = [
            "type" => "success",
            "message" => "Le livre " . $livre->getTitre() . " a été supprimé avec succès!"
        ];
        header('location: ' . SITE_URL . 'livres');
    }

    /**
     * Récupère tous les livres et les affiche sur la page d'accueil
     */
    public function getAllLivres()
    {
        // Vérifie si l'utilisateur n'est ni admin ni utilisateur standard
        if (!$this->utilisateurController->isRoleAdmin() || !$this->utilisateurController->isRoleUser()) {
            // Réinitialise la liste des livres et charge tous les livres de la BDD
            $this->repositoryLivres->setLivres([]);
            $livresAll = $this->repositoryLivres->chargementLivresBdd();
        } else {
            // Utilise la liste des livres déjà chargée
            $livresAll = $this->repositoryLivres->getLivres();
        }

        // Affiche la vue de la page d'accueil
        require '../app/Views/accueil.php';
    }
}
