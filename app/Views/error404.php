<?php

/**
 * Vue pour l'erreur 404 (Page non trouvée)
 *
 * Ce fichier affiche un message d'erreur 404 personnalisé et fournit un lien
 * pour contacter l'administrateur du site.
 *
 * @package MonApplication
 * @subpackage Views
 */

// Début de la capture de sortie
ob_start();
?>

<div class="d-flex flex-column justify-content-center">
    <!-- Affichage du message d'erreur -->
    <p><?= $message ?>&nbsp;</p>
    <!-- Lien pour contacter l'administrateur -->
    <p>Contacter l'administrateur : <a href="mailto:contact@monsite.fr">ici</a></p>
</div>

<?php
// Définition du titre de la page
$titre = "Page introuvable";

// Récupération du contenu capturé
$content = ob_get_clean();

// Inclusion du template principal
require_once 'template.php';
