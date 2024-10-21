<?php

/**
 * Fichier d'initialisation
 * 
 * Ce fichier contient des définitions et des configurations initiales pour l'application.
 * 
 * @package MonApplication
 * @version 1.0
 */

declare(strict_types=1);

/**
 * Définit l'URL du site
 * 
 * Cette constante crée une URL absolue pour le site en se basant sur les informations du serveur.
 * Elle prend en compte le protocole (HTTP ou HTTPS) et supprime "index.php" de l'URL si présent.
 */
define('SITE_URL', str_replace("index.php", "", (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));
