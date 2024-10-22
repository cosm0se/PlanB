<?php

/**
 * Vue pour afficher les détails d'un livre
 *
 * Cette vue affiche les informations détaillées d'un livre, y compris son image,
 * son titre, le nombre de pages et l'uploader.
 *
 * @package App\Views
 * @var object $livre L'objet livre contenant les détails à afficher
 */

// Début de la capture de sortie
ob_start();
?>
<div class="livre-container">
    <div class="livre-image-container">
        <!-- Affichage de l'image du livre -->
        <img class="livre-image" src="<?= SITE_URL ?>images/<?= $livre->getUrlImage() ?>" alt="<?= $livre->getTextAlternatif() ?>">
    </div>
    <div class="livre-details">
        <!-- Affichage des détails du livre -->
        <p><strong>Titre :</strong> <?= $livre->getTitre() ?></p>
        <p><strong>Nombre de pages :</strong> <?= $livre->getNbreDePages() ?></p>
        <p><strong>Uploadé par :</strong> <?= $livre->getUploader() ?></p>
    </div>
</div>
<div id="descriptionLivre">

</div>
<?php
// Définition du titre de la page
$titre = $livre->getTitre();

// Fin de la capture de sortie et stockage dans la variable $content
$content = ob_get_clean();

// Inclusion du template principal
require_once 'template.php';
