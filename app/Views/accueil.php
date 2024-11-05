<?php

/**
 * Vue de la page d'accueil
 * 
 * Cette vue affiche une grille de livres avec leurs informations de base.
 * Chaque livre est présenté dans une carte avec son titre, son image, 
 * l'uploader et un lien pour plus de détails.
 * 
 * @var array $livresAll Tableau contenant tous les objets Livre à afficher
 */

// Début de la capture de sortie
ob_start();
?>

<div class="livres-container">
    <?php foreach ($livresAll as $livre) : ?>
        <!-- Carte pour chaque livre -->
        <div class="livre-card">
            <h3 class="livre-title"><?= $livre->getTitre() ?></h3>
            <img class="livre-image" src="<?= SITE_URL ?>images/<?= $livre->getUrlImage(); ?>" alt="Image du livre">
            <div class="livre-body">
                <p>Uploadé par : <?= $livre->getUploader(); ?></p>
                <a class="livre-link" href="<?= SITE_URL ?>livres/l/<?= $livre->getId(); ?>">En savoir plus ...</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php
$titre = "Bienvenue sur flixbooks, Votre plateforme de recommandations de livres";

// Récupération du contenu mis en mémoire tampon
$content = ob_get_clean();
require_once 'template.php';
