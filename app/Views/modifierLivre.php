<?php

/**
 * Vue pour modifier un livre
 * 
 * Cette vue affiche un formulaire permettant de modifier les détails d'un livre existant.
 * Elle gère également l'affichage des erreurs de validation et la préservation des anciennes valeurs.
 * 
 * @var object $livre L'objet livre à modifier
 * @var string $csrfToken Le jeton CSRF pour la protection contre les attaques CSRF
 */

// Début de la capture de sortie
ob_start();

// Inclusion du fichier pour afficher les alertes
require '../app/Views/showAlert.php';
?>

<div class="d-flex flex-column justify-content-center">
    <!-- Formulaire de modification du livre -->
    <form method="POST" action="<?= SITE_URL ?>livres/mv" enctype="multipart/form-data">
        <!-- Champ pour le titre du livre -->
        <div class="form-group my-4">
            <label for="titre">Titre : </label>
            <input type="text" value="<?= isset($_SESSION['old_values']['titre']) ? htmlspecialchars($_SESSION['old_values']['titre']) : htmlspecialchars($livre->getTitre()); ?>" class="form-control" id="titre" name="titre">
            <!-- Affichage des erreurs pour le titre -->
            <?php if (isset($_SESSION['erreurs']['titre'])): ?>
                <div class="text-danger">
                    <?php foreach ($_SESSION['erreurs']['titre'] as $erreur): ?>
                        <p><?= htmlspecialchars($erreur) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Champ pour le nombre de pages -->
        <div class="form-group my-4">
            <label for="nbre-de-pages">Nombre de pages : </label>
            <input type="number" value="<?= isset($_SESSION['old_values']['nbre-de-pages']) ? htmlspecialchars($_SESSION['old_values']['nbre-de-pages']) : htmlspecialchars($livre->getNbreDePages()); ?>" class="form-control" id="nbre-de-pages" name="nbre-de-pages">
            <!-- Affichage des erreurs pour le nombre de pages -->
            <?php if (isset($_SESSION['erreurs']['nbre-de-pages'])): ?>
                <div class="text-danger">
                    <?php foreach ($_SESSION['erreurs']['nbre-de-pages'] as $erreur): ?>
                        <p><?= htmlspecialchars($erreur) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Champ pour le texte alternatif de l'image -->
        <div class="form-group my-4">
            <label for="text-alternatif">Texte alternatif : </label>
            <textarea class="form-control" id="text-alternatif" name="text-alternatif"><?= isset($_SESSION['old_values']['text-alternatif']) ? htmlspecialchars($_SESSION['old_values']['text-alternatif']) : htmlspecialchars($livre->getTextAlternatif()); ?></textarea>
            <!-- Affichage des erreurs pour le texte alternatif -->
            <?php if (isset($_SESSION['erreurs']['text-alternatif'])): ?>
                <div class="text-danger">
                    <?php foreach ($_SESSION['erreurs']['text-alternatif'] as $erreur): ?>
                        <p><?= htmlspecialchars($erreur) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Aperçu de l'image actuelle du livre -->
        <img id="image-preview" src="<?= SITE_URL ?>images/<?= htmlspecialchars($livre->getUrlImage()); ?>" alt="<?= htmlspecialchars($livre->getTextAlternatif()); ?>">

        <!-- Champ pour télécharger une nouvelle image -->
        <div class="form-group my-4">
            <label for="image">Image : </label>
            <input type="file" class="form-control-file" id="image" name="image">
            <!-- Affichage des erreurs pour l'image -->
            <?php if (isset($_SESSION['erreurs']['image'])): ?>
                <div class="text-danger">
                    <?php foreach ($_SESSION['erreurs']['image'] as $erreur): ?>
                        <p><?= htmlspecialchars($erreur) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Champ caché pour l'ID du livre -->
        <input type="hidden" name="id_livre" value="<?= htmlspecialchars($livre->getId()); ?>">

        <!-- Jeton CSRF pour la protection contre les attaques CSRF -->
        <?= $csrfToken ?>

        <!-- Bouton de soumission du formulaire -->
        <button class="btn btn-info mt-5">Modifier livre</button>
    </form>
</div>

<?php
// Définition du titre de la page
$titre = "Modifier le livre " . htmlspecialchars($livre->getTitre());

// Fin de la capture de sortie et stockage dans la variable $content
$content = ob_get_clean();

// Inclusion du template principal
require_once 'template.php';
?>