<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Classe Csrf
 * 
 * Cette classe gère la protection CSRF (Cross-Site Request Forgery) pour l'application.
 * Elle fournit des méthodes pour générer, vérifier et gérer les jetons CSRF.
 */
class Csrf
{
    // Nom de la variable de session pour stocker le jeton CSRF
    private const SESSION_NAME = 'biblio_csrf_token';

    // Nom du champ de formulaire pour le jeton CSRF
    private const FIELD_NAME = 'biblio_csrf_check';

    /**
     * Définit le jeton CSRF dans la session si ce n'est pas déjà fait.
     */
    private static function setSession()
    {
        if (!isset($_SESSION[self::SESSION_NAME])) {
            // Génère un jeton aléatoire de 32 octets et le convertit en hexadécimal
            $_SESSION[self::SESSION_NAME] = bin2hex(random_bytes(32));
        }
    }

    /**
     * Supprime le jeton CSRF de la session.
     */
    public static function unsetSession()
    {
        if (isset($_SESSION[self::SESSION_NAME])) {
            unset($_SESSION[self::SESSION_NAME]);
        }
    }

    /**
     * Vérifie si le jeton CSRF soumis correspond à celui stocké en session.
     * En cas d'échec, termine le script avec une erreur 403 Forbidden.
     */
    public static function check()
    {
        self::setSession();
        if (!isset($_POST[self::FIELD_NAME]) || $_POST[self::FIELD_NAME] !== $_SESSION[self::SESSION_NAME]) {
            self::unsetSession();
            header('HTTP/1.1 403 Forbidden');
            exit('<h1>Forbidden</h1>');
        }
        self::unsetSession();
    }

    /**
     * Génère un jeton CSRF et retourne optionnellement un champ de formulaire caché.
     *
     * @param bool $input Si true, retourne un champ input HTML, sinon retourne null.
     * @return string|null Le champ input HTML ou null.
     */
    public static function token($input = true)
    {
        self::setSession();
        if ($input) {
            // Construit un champ input HTML caché contenant le jeton CSRF
            $txt = '';
            $txt .= '<input type="hidden" name="';
            $txt .= self::FIELD_NAME;
            $txt .= '" value="';
            $txt .= $_SESSION[self::SESSION_NAME];
            $txt .= '" />';
            return $txt;
        }
        return null; // Ajout d'un retour explicite si $input est false
    }
}
