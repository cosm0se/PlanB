<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Csrf;
use App\Service\ValidationDonnees;
use PHPMailer\PHPMailer\PHPMailer;
use App\Repository\UtilisateurRepository;

/**
 * Contrôleur pour la gestion des utilisateurs
 * 
 * Cette classe gère toutes les opérations liées aux utilisateurs, y compris
 * l'inscription, la connexion, la gestion des profils et l'administration des membres.
 */
class UtilisateurController
{
    private UtilisateurRepository $utilisateurRepository;
    private ValidationDonnees $validationDonnees;

    /**
     * Constructeur de la classe UtilisateurController
     * 
     * Initialise les dépendances nécessaires pour le contrôleur.
     */
    public function __construct()
    {
        $this->utilisateurRepository = new UtilisateurRepository();
        $this->validationDonnees = new ValidationDonnees();
    }

    /**
     * Affiche la page d'inscription
     * 
     * Redirige vers la page des livres si l'utilisateur est déjà connecté.
     */
    public function afficherInscription()
    {
        if ($this->isRoleAdmin() || $this->isRoleUser()) {
            header('location: ' . SITE_URL . 'livres');
        }
        $csrfToken = Csrf::token();
        require '../app/Views/afficherInscription.php';
    }

    /**
     * Affiche la page de gestion des membres pour l'administrateur
     * 
     * Vérifie si l'utilisateur est un administrateur avant d'afficher la page.
     */
    public function afficherGestionMembres()
    {
        if (!$this->isRoleAdmin()) header('location: ' . SITE_URL . 'connexion');
        $utilisateurs = $this->utilisateurRepository->getAllUtilisateurs();
        $pasDutilisateur = (count($utilisateurs) > 0) ? false : true;
        if (empty($_SESSION['alert'])) $_SESSION['alert'] = [
            "type" => "success",
            "message" => "Bienvenue " . $_SESSION['utilisateur']['identifiant']
        ];
        require "../app/Views/afficherGestionUtilisateurs.php";
    }

    /**
     * Modifie un utilisateur par l'administrateur
     * 
     * @param int $iDutilisateur L'ID de l'utilisateur à modifier
     */
    public function modifierUtilisateurByAdmin($iDutilisateur)
    {
        $resultat = $this->utilisateurRepository->modifierUtilisateurByAdminInBdd($iDutilisateur);
        if ($resultat) {
            $_SESSION['alert'] = [
                "type" => "success",
                "message" => "L'utilisateur " . $_POST['identifiant'] . " a bien été modifié."
            ];
            header('location: ' . SITE_URL . 'gestion-membres');
        } else {
            $_SESSION['erreurs']['update-utilisateur'][] = 'Erreur de modification';
            header('location: ' . SITE_URL . 'gestion-membres');
            exit;
        }
    }

    /**
     * Supprime un utilisateur par l'administrateur
     * 
     * @param int $iDutilisateur L'ID de l'utilisateur à supprimer
     */
    public function supprimerUtilisateurByAdmin($iDutilisateur)
    {
        $resultat = $this->utilisateurRepository->supprimerUtilisateurByAdminInBdd($iDutilisateur);
        if ($resultat) {
            $_SESSION['alert'] = [
                "type" => "success",
                "message" => "L'utilisateur a bien été supprimé."
            ];
            header('location: ' . SITE_URL . 'gestion-membres');
        } else {
            $_SESSION['erreurs']['delete-utilisateur'][] = 'Erreur de suppression';
            header('location: ' . SITE_URL . 'gestion-membres');
            exit;
        }
    }

    /**
     * Affiche le profil de l'utilisateur connecté
     */
    public function afficherProfil()
    {
        $csrfToken = Csrf::token();
        $utilisateur = $this->utilisateurRepository->getUtilisateurByEmail($_SESSION['utilisateur']['email']);
        require '../app/Views/afficherProfil.php';
        if (array_key_exists('alert', $_SESSION)) unset($_SESSION['alert']);
    }

    /**
     * Modifie le profil de l'utilisateur
     * 
     * @param int $idUtilisateur L'ID de l'utilisateur à modifier
     */
    public function modificationProfil($idUtilisateur)
    {
        Csrf::check();
        unset($_SESSION['erreurs']);
        unset($_SESSION['old_values']); // Reset des anciennes valeurs

        $erreurs = $this->validationDonnees->valider([
            'identifiant' => ['match:/^[a-zA-Z0-9\-]+$/'],
            'email' => ['match:/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/'],
            'password' => (!empty($_POST['password'])) ? ['match:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.* ).{8,}$/'] : [],
        ], $_POST);

        if ($_POST['password_check'] !== $_POST['password']) {
            $_SESSION['erreurs']['password_check'][] = 'Les 2 mots de passe ne correspondent pas!';
            $_SESSION['old_values'] = $_POST;
            header('location: ' . SITE_URL . 'profil');
            exit;
        }

        if (is_array($erreurs) && count($erreurs) > 0) {
            $_SESSION['erreurs'] = $erreurs;
            $_SESSION['old_values'] = $_POST;
            header('location: ' . SITE_URL . 'profil');
            exit;
        }

        $_POST['email'] = trim(htmlspecialchars($_POST['email']));
        $_POST['password'] = trim(htmlspecialchars($_POST['password']));
        $utilisateur = $this->utilisateurRepository->modifierUtilisateurBdd($idUtilisateur);

        if ($utilisateur) {
            $_SESSION['utilisateur']['email'] = $utilisateur->getEmail();
            $_SESSION['utilisateur']['identifiant'] = $utilisateur->getIdentifiant();
            $_SESSION['alert'] = [
                "type" => "success",
                "message" => "Votre profil a bien été modifié."
            ];
            header('location: ' . SITE_URL . 'profil');
        } else {
            $_SESSION['erreurs']['update-utilisateur'][] = 'Erreur de modification';
            $_SESSION['old_values'] = $_POST;
            header('location: ' . SITE_URL . 'profil');
            exit;
        }
    }

    /**
     * Affiche la page de connexion
     * 
     * Redirige vers la page des livres si l'utilisateur est déjà connecté.
     */
    public function afficherConnexion()
    {
        if ($this->isRoleAdmin() || $this->isRoleUser()) {
            header('location: ' . SITE_URL . 'livres');
        }
        $csrfToken = Csrf::token();
        require '../app/Views/afficherConnexion.php';
        if (array_key_exists('alert', $_SESSION)) unset($_SESSION['alert']);
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout()
    {
        if (isset($_SESSION['utilisateur'])) {
            unset($_SESSION['utilisateur']);
        }
        header('location: ' . SITE_URL . '');
    }

    /**
     * Valide l'inscription d'un nouvel utilisateur
     */
    public function inscriptionValidation()
    {
        CSRF::check();
        unset($_SESSION['erreurs']);
        unset($_SESSION['old_values']); // Reset des anciennes valeurs

        $erreurs = $this->validationDonnees->valider([
            'identifiant' => ['match:/^[a-zA-Z\-]+$/'],
            'email' => ['match:/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/'],
            'password' => ['match:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.* ).{12,}$/'],
        ], $_POST);

        if ($_POST['password_check'] !== $_POST['password']) {
            $_SESSION['erreurs']['password_check'][] = 'Les 2 mots de passe ne correspondent pas!';
            $_SESSION['old_values'] = $_POST;
            header('location: ' . SITE_URL . 'inscription');
            exit;
        }

        if (is_array($erreurs) && count($erreurs) > 0) {
            $_SESSION['erreurs'] = $erreurs;
            $_SESSION['old_values'] = $_POST;
            header('location: ' . SITE_URL . 'inscription');
            exit;
        }

        $_POST['email'] = trim(htmlspecialchars($_POST['email']));
        $_POST['password'] = trim(htmlspecialchars($_POST['password']));

        $token = bin2hex(random_bytes(16));

        $resultat = $this->utilisateurRepository->setUtilisateurBdd($token);

        if ($resultat) {
            $emailSend = $this->envoyerEmailValidation($_POST['email'], $token);
            if ($emailSend) {
                $_SESSION['alert'] = [
                    "type" => "success",
                    "message" => "Merci " . $_POST['identifiant'] . ", votre compte a été créé, un email vous à été envoyé pour la validation de votre compte ..."
                ];
                header('location: ' . SITE_URL . 'connexion');
            } else {
                $_SESSION['erreurs']['email'][] = 'Email non envoyé';
                $_SESSION['old_values'] = $_POST;
                header('location: ' . SITE_URL . 'inscription');
                exit;
            }
        } else {
            $_SESSION['erreurs']['email'][] = 'Email ou mot de passe incorrect';
            $_SESSION['old_values'] = $_POST;
            header('location: ' . SITE_URL . 'inscription');
            exit;
        }
    }

    /**
     * Envoie un email de validation à l'utilisateur
     * 
     * @param string $email L'adresse email de l'utilisateur
     * @param string $token Le token de validation
     * @return bool True si l'email a été envoyé avec succès, false sinon
     */
    public function envoyerEmailValidation($email, $token)
    {

        $mail = new PHPMailer(true);


        // Configuration du serveur SMTP (MailHog ici)
        $mail->isSMTP();
        $mail->Host = 'mailhog';  // Si vous utilisez Docker et que MailHog est dans le même réseau
        $mail->Port = 1025;       // Port SMTP de MailHog
        $mail->SMTPAuth = false;  // Pas d'authentification nécessaire pour MailHog

        // Paramètres de l'expéditeur et du destinataire
        $mail->setFrom('noreply@flixbooks.fr', 'FlixBooks');
        $mail->addAddress($email);  // E-mail du destinataire

        // Contenu de l'e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Validation de votre inscription';
        $urlVerification = SITE_URL . "verification-email/" . $token;
        $mail->Body    = "Merci de vous être inscrit. Cliquez sur le lien suivant pour valider votre adresse e-mail : <a href='$urlVerification'>$urlVerification</a>";
        $mail->AltBody = "Merci de vous être inscrit. Cliquez sur le lien suivant pour valider votre adresse e-mail : $urlVerification";

        // Envoi de l'e-mail
        return $mail->send();
    }


    /**
     * Vérifie l'email de l'utilisateur avec le token fourni
     * 
     * @param string $token Le token de validation
     */
    public function verifierEmail($token)
    {
        if (!isset($token)) {
            $_SESSION['erreurs'][] = ['Token' => 'Aucun token de validation fourni.'];
            header('location: ' . SITE_URL . 'connexion');
            exit;
        }

        $utilisateur = $this->utilisateurRepository->getUtilisateurByToken($token);

        if ($utilisateur) {
            // Mise à jour de l'utilisateur : email_valide = 1 et suppression du token
            $this->utilisateurRepository->validerEmailUtilisateur($utilisateur->getIdUtilisateur());

            $_SESSION['alert'] = [
                "type" => "success",
                "message" => "Votre e-mail a été vérifié. Vous pouvez maintenant vous connecter."
            ];
            header('location: ' . SITE_URL . 'connexion');
        } else {
            $_SESSION['erreurs'][] = ['Token' => 'Le token de validation est invalide ou a expiré.'];
            header('location: ' . SITE_URL . 'connexion');
            exit;
        }
    }


    /**
     * Valide la connexion de l'utilisateur
     */
    public function connexionValidation()
    {
        CSRF::check();
        unset($_SESSION['erreurs']);
        unset($_SESSION['old_values']); // Reset des anciennes valeurs

        $erreurs = $this->validationDonnees->valider([
            'email' => ['match:/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/'],
            'password' => ['match:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.* ).{12,}$/'],
        ], $_POST);

        if (is_array($erreurs) && count($erreurs) > 0) {
            $_SESSION['erreurs'] = $erreurs;
            $_SESSION['old_values'] = $_POST;
            header('location: ' . SITE_URL . 'connexion');
            exit;
        }

        $_POST['email'] = trim(htmlspecialchars($_POST['email']));
        $_POST['password'] = trim(htmlspecialchars($_POST['password']));
        $utilisateur = $this->utilisateurRepository->getUtilisateurByEmail($_POST['email']);

        if ($utilisateur) {
            // Vérification que l'e-mail a été validé
            if (!$utilisateur->getIsValide()) {
                $_SESSION['erreurs'][] = ['email' => 'Veuillez vérifier votre e-mail pour activer votre compte.'];
                header('location: ' . SITE_URL . 'connexion');
                exit;
            }
            if (password_verify($_POST['password'], $utilisateur->getPassword())) {
                $_SESSION['utilisateur']['id_utilisateur'] = $utilisateur->getIdUtilisateur();
                $_SESSION['utilisateur']['email'] = $utilisateur->getEmail();
                $_SESSION['utilisateur']['role'] = $utilisateur->getRole();
                $_SESSION['utilisateur']['identifiant'] = $utilisateur->getIdentifiant();
                $_SESSION['utilisateur']['is_valide'] = $utilisateur->getIsValide();
                if ($this->isRoleAdmin()) {
                    header('location: ' . SITE_URL . 'gestion-membres');
                } else {
                    header('location: ' . SITE_URL . 'livres');
                }
            } else {
                $_SESSION['erreurs']['connexion'][] = 'Email ou mot de passe incorrect';
                $_SESSION['old_values'] = $_POST;
                header('location: ' . SITE_URL . 'connexion');
                exit;
            }
        } else {
            $_SESSION['erreurs']['connexion'][] = 'Email ou mot de passe incorrect';
            $_SESSION['old_values'] = $_POST;
            header('location: ' . SITE_URL . 'connexion');
            exit;
        }
    }

    /**
     * Vérifie si l'utilisateur a le rôle utilisateur
     * 
     * @return bool True si l'utilisateur a le rôle utilisateur, false sinon
     */
    public function isRoleUser(): bool
    {
        if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'ROLE_USER' && $_SESSION['utilisateur']['is_valide']) {
            return true;
        }
        if (isset($_SESSION['utilisateur']) && !$_SESSION['utilisateur']['is_valide']) $_SESSION['alert'] = [
            "type" => "warning",
            "message" => "Désolé " . $_SESSION['utilisateur']['identifiant'] . ", votre compte n'est pas activé, veuillez contacter l'administrateur"
        ];
        return false;
    }

    /**
     * Vérifie si l'utilisateur a le rôle administrateur
     * 
     * @return bool True si l'utilisateur a le rôle administrateur, false sinon
     */
    public function isRoleAdmin(): bool
    {
        if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'ROLE_ADMIN') {
            return true;
        }
        return false;
    }

    /**
     * Redirige vers la page de connexion si l'utilisateur n'est pas connecté
     */
    public function redirectLogin()
    {
        $isAdmin = $this->isRoleAdmin();
        $isUser = $this->isRoleUser();
        if (!$isAdmin && !$isUser) {
            header('location: ' . SITE_URL . 'connexion');
            exit;
        }
    }
}
