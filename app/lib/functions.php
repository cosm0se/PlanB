<?php

declare(strict_types=1);

/**
 * Affiche des informations de débogage formatées pour une variable donnée.
 *
 * Cette fonction affiche le contenu d'une variable dans un conteneur stylisé,
 * avec des informations sur l'emplacement de l'appel de débogage.
 *
 * @param mixed $var   La variable à déboguer
 * @param int   $mode  Le mode d'affichage (1 pour print_r, 2 pour var_dump)
 * @return void
 */
function debug($var, $mode = 1)
{
    // Début du conteneur de débogage stylisé
    echo '<div style="background: #acacf1; padding: 5px; margin: 10px; max-width: 100%; overflow-x: auto; word-wrap: break-word; border-radius: 255px 25px 225px 25px / 25px 225px 25px 255px; padding: 20px;">';

    // Récupération des informations de trace pour localiser l'appel de débogage
    $trace = debug_backtrace();
    $trace = array_shift($trace);

    // Affichage des informations sur l'emplacement de l'appel de débogage
    echo "<p style='font-size: 14px; margin: 0 0 10px;'>Debug demandé dans le fichier : " . htmlspecialchars($trace['file']) . " à la ligne " . $trace['line'] . ".</p><hr />";

    // Début de la section d'affichage de la variable
    echo "<div style='font-size: 12px;'>";

    // Choix du mode d'affichage (print_r ou var_dump)
    if ($mode === 1) {
        echo "<pre style='white-space: pre-wrap;'>";
        print_r($var);
        echo "</pre>";
    } else {
        echo "<pre style='white-space: pre-wrap;'>";
        var_dump($var);
        echo "</pre>";
    }

    // Fermeture des balises
    echo "</div></div>";
}

// Fin de la fonction de débogage
