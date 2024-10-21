<?php

/**
 * Point d'entrée principal de l'application
 * 
 * Ce fichier sert de point d'entrée pour l'application web.
 * Il charge l'autoloader de Composer et inclut le fichier principal de l'application.
 * 
 * @package MonApplication
 * @version 1.0
 * @author Votre Nom
 * @copyright 2023 Votre Entreprise
 */

// Charge l'autoloader de Composer pour gérer les dépendances
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Inclut le fichier principal de l'application
require_once dirname(__DIR__) . '/index.php';
