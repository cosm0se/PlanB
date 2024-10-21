<?php

/**
 * Vue pour afficher le formulaire de connexion
 *
 * Ce fichier contient le formulaire HTML pour la connexion des utilisateurs.
 * Il gère également l'affichage des erreurs de validation et la conservation
 * des anciennes valeurs en cas de soumission incorrecte.
 *
 * @package App\Views
 */

// Début de la capture de sortie
ob_start();

// Inclusion du fichier pour afficher les alertes
require '../app/Views/showAlert.php';
?>

<div class="d-flex flex-column justify-content-center">
    <form class="m-auto w-50" method="post" action="<?= SITE_URL ?>connexion/v">
        <fieldset>
            <legend>Connexion</legend>

            <?php
            // Affichage des erreurs générales de connexion
            if (isset($_SESSION['erreurs']['connexion'])): ?>
                <div class="text-danger">
                    <?php foreach ($_SESSION['erreurs']['connexion'] as $erreur): ?>
                        <p><?= htmlspecialchars($erreur) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Champ pour l'email -->
            <div class="form-group">
                <label for="email" class="form-label mt-4">Email : </label>
                <input type="email" autofocus name="email" class="form-control" id="email" aria-describedby="emailHelp"
                    placeholder="Votre adresse mail"
                    value="<?= isset($_SESSION['old_values']['email']) ? htmlspecialchars($_SESSION['old_values']['email']) : '' ?>">
                <small id="emailHelp" class="form-text text-muted">Saisissez l'adresse mail choisie à l'inscription.</small>

                <?php
                // Affichage des erreurs spécifiques à l'email
                if (isset($_SESSION['erreurs']['email'])): ?>
                    <div class="text-danger">
                        <?php foreach ($_SESSION['erreurs']['email'] as $erreur): ?>
                            <p><?= htmlspecialchars($erreur) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Champ pour le mot de passe -->
            <div class="form-group">
                <label for="password" class="form-label mt-4">Mot de passe : </label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Votre mot de passe" autocomplete="off">

                <?php
                // Affichage des erreurs spécifiques au mot de passe
                if (isset($_SESSION['erreurs']['password'])): ?>
                    <div class="text-danger">
                        <?php foreach ($_SESSION['erreurs']['password'] as $erreur): ?>
                            <p><?= htmlspecialchars($erreur) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Insertion du jeton CSRF -->
            <?= $csrfToken ?>

            <button type="submit" class="btn btn-primary mt-5">Se connecter</button>
        </fieldset>
    </form>
</div>

<?php
// Définition du titre de la page
$titre = "Connexion";

// Récupération du contenu mis en mémoire tampon
$content = ob_get_clean();

// Inclusion du template principal
require_once 'template.php';
