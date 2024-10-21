<?php

/**
 * Affiche une alerte si elle est présente dans la session.
 * 
 * Ce fichier vérifie si une alerte est stockée dans la session,
 * l'affiche si elle existe, puis la supprime de la session.
 * 
 * @uses $_SESSION['alert'] Tableau contenant le type et le message de l'alerte
 */

// Vérifie si une alerte est présente dans la session
if (array_key_exists('alert', $_SESSION)) : ?>
    <!-- Affiche l'alerte avec le type et le message appropriés -->
    <div class="alert alert-<?= $_SESSION['alert']['type'] ?>" role="alert">
        <?= $_SESSION['alert']['message'] ?>
    </div>
<?php
    // Supprime l'alerte de la session après l'avoir affichée
    unset($_SESSION['alert']);
endif;
?>