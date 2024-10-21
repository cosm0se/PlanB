<?php

/**
 * Affichage des erreurs
 * 
 * Ce fichier est responsable de l'affichage des messages d'erreur stockés dans la session.
 * Il parcourt les erreurs et les affiche dans des alertes Bootstrap.
 * 
 * @category Views
 * @package  App\Views
 */

// Vérifie si des erreurs sont présentes dans la session
if (isset($_SESSION['erreurs'])) {
    // Parcourt le premier niveau du tableau d'erreurs
    foreach ($_SESSION['erreurs'] as $erreursTab) {
        // Parcourt le deuxième niveau du tableau d'erreurs
        foreach ($erreursTab as $erreurs) {
            // Initialise la div d'alerte avec les classes Bootstrap
            $divErreur = "<div class='alert alert-danger w-100 m-auto' style='max-width: 781px'><ul>";

            // Parcourt chaque message d'erreur individuel
            foreach ($erreurs as $erreur) {
                // Ajoute le message d'erreur à la liste
                $divErreur .= "<li>$erreur</li>";
            }

            // Ferme la liste et la div d'alerte
            $divErreur .= '</ul></div>';

            // Supprime les erreurs de la session après les avoir affichées
            unset($_SESSION['erreurs']);

            // Affiche la div d'alerte contenant les erreurs
            echo $divErreur;
        }
    }
}
