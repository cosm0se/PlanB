<?php

/**
 * Vue pour ajouter un nouveau livre
 * 
 * Cette vue affiche un formulaire permettant d'ajouter un nouveau livre à la bibliothèque.
 * Elle gère également l'affichage des erreurs de validation et la conservation des anciennes valeurs.
 * 
 * @package App\Views
 */

// Début de la mise en mémoire tampon de sortie
ob_start();

// Inclusion du fichier pour afficher les alertes
require '../app/Views/showAlert.php';
?>

<div class="d-flex flex-column justify-content-center">
    <!-- Formulaire d'ajout de livre -->
    <form method="POST" action="<?= SITE_URL ?>livres/av" enctype="multipart/form-data">
        <!-- Champ pour le titre du livre -->
        <div class="form-group my-4">
            <label for="titre">Titre : </label>
            <input type="text" class="form-control" id="titre" name="titre"
                value="<?= isset($_SESSION['old_values']['titre']) ? htmlspecialchars($_SESSION['old_values']['titre']) : '' ?>">
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
            <input type="number" class="form-control" id="nbre-de-pages" name="nbre-de-pages"
                value="<?= isset($_SESSION['old_values']['nbre-de-pages']) ? htmlspecialchars($_SESSION['old_values']['nbre-de-pages']) : '' ?>">
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
            <textarea class="form-control" id="text-alternatif" name="text-alternatif"><?= isset($_SESSION['old_values']['text-alternatif']) ? htmlspecialchars($_SESSION['old_values']['text-alternatif']) : '' ?></textarea>
            <!-- Affichage des erreurs pour le texte alternatif -->
            <?php if (isset($_SESSION['erreurs']['text-alternatif'])): ?>
                <div class="text-danger">
                    <?php foreach ($_SESSION['erreurs']['text-alternatif'] as $erreur): ?>
                        <p><?= htmlspecialchars($erreur) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Prévisualisation de l'image -->
        <img src="" id="image-preview" alt="">

        <!-- Champ pour l'upload de l'image -->
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

        <!-- Jeton CSRF pour la sécurité -->
        <?= $csrfToken ?>

        <!-- Bouton de soumission du formulaire -->
        <button class="btn btn-info mt-5">Créer livre</button>
    </form>
</div>

<?php
// Définition du titre de la page
$titre = "Ajout livre";

// Récupération du contenu mis en mémoire tampon
$content = ob_get_clean();

// Inclusion du template principal
require_once 'template.php';
?>