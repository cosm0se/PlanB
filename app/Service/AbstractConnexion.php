<?php

declare(strict_types=1);

namespace App\Service;

use PDO;

/**
 * Classe abstraite pour gérer la connexion à la base de données.
 * 
 * Cette classe fournit une méthode pour établir et récupérer une connexion PDO
 * à la base de données MySQL. Elle utilise le pattern Singleton pour s'assurer
 * qu'une seule connexion est établie.
 */
abstract class AbstractConnexion
{
    /** @var PDO|null Instance de connexion PDO */
    private static $connexion;

    /**
     * Établit la connexion à la base de données.
     * 
     * Cette méthode privée crée une nouvelle instance PDO en utilisant les
     * variables d'environnement pour les paramètres de connexion.
     * 
     * @return void
     */
    private static function setConnexionBdd()
    {
        // Création de la connexion PDO
        self::$connexion = new PDO("mysql:host=$_ENV[MYSQL_HOST];dbname=$_ENV[MYSQL_DATABASE];chartset=utf8", $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD']);

        // Configuration du mode d'erreur de PDO
        self::$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    /**
     * Récupère l'instance de connexion à la base de données.
     * 
     * Si la connexion n'existe pas encore, elle est d'abord établie.
     * 
     * @return PDO Instance de connexion PDO
     */
    protected function getConnexionBdd()
    {
        // Vérification si la connexion existe déjà
        if (self::$connexion === null) {
            // Si non, on établit la connexion
            self::setConnexionBdd();
        }
        // Retourne l'instance de connexion
        return self::$connexion;
    }
}
