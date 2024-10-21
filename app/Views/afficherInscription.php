<?php

/**
 * Vue pour afficher le formulaire d'inscription
 *
 * Cette vue gère l'affichage du formulaire d'inscription, y compris la gestion des erreurs
 * et la conservation des anciennes valeurs en cas de soumission invalide.
 *
 * @package App\Views
 */

// Début de la capture de sortie
ob_start();

// Inclusion du fichier pour afficher les alertes
require '../app/Views/showAlert.php';
?>

<!-- Conteneur principal du formulaire -->
<div class="d-flex flex-column justify-content-center">
    <!-- Formulaire d'inscription -->
    <form class="m-auto w-50" method="post" action="<?= SITE_URL ?>inscription/v">
        <fieldset>
            <legend>Inscription</legend>

            <!-- Champ Identifiant -->
            <div class="form-group">
                <label for="identifiant" class="form-label mt-4">Identifiant : </label>
                <input type="text" autofocus name="identifiant" class="form-control" id="identifiant"
                    aria-describedby="identifiantHelp" placeholder="Votre identifiant"
                    value="<?= isset($_SESSION['old_values']['identifiant']) ? htmlspecialchars($_SESSION['old_values']['identifiant']) : '' ?>">

                <!-- Affichage des erreurs pour le champ identifiant -->
                <?php if (isset($_SESSION['erreurs']['identifiant'])): ?>
                    <div class="text-danger">
                        <?php foreach ($_SESSION['erreurs']['identifiant'] as $erreur): ?>
                            <p><?= htmlspecialchars($erreur) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Champ Email -->
            <div class="form-group">
                <label for="email" class="form-label mt-4">Email : </label>
                <input type="email" name="email" class="form-control" id="email"
                    aria-describedby="emailHelp" placeholder="Votre adresse mail"
                    value="<?= isset($_SESSION['old_values']['email']) ? htmlspecialchars($_SESSION['old_values']['email']) : '' ?>">

                <!-- Affichage des erreurs pour le champ email -->
                <?php if (isset($_SESSION['erreurs']['email'])): ?>
                    <div class="text-danger">
                        <?php foreach ($_SESSION['erreurs']['email'] as $erreur): ?>
                            <p><?= htmlspecialchars($erreur) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Champ Mot de passe -->
            <div class="form-group">
                <label for="password" class="form-label mt-4">Mot de passe : </label>
                <input type="password" name="password" class="form-control" id="password"
                    placeholder="Votre mot de passe" autocomplete="off">

                <!-- Affichage des erreurs pour le champ mot de passe -->
                <?php if (isset($_SESSION['erreurs']['password'])): ?>
                    <div class="text-danger">
                        <?php foreach ($_SESSION['erreurs']['password'] as $erreur): ?>
                            <p><?= htmlspecialchars($erreur) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Champ Confirmation du mot de passe -->
            <div class="form-group">
                <label for="password_check" class="form-label mt-4">Confirmer le mot de passe : </label>
                <input type="password" name="password_check" class="form-control" id="password_check"
                    placeholder="Répéter votre mot de passe" autocomplete="off">

                <!-- Affichage des erreurs pour le champ de confirmation du mot de passe -->
                <?php if (isset($_SESSION['erreurs']['password_check'])): ?>
                    <div class="text-danger">
                        <?php foreach ($_SESSION['erreurs']['password_check'] as $erreur): ?>
                            <p><?= htmlspecialchars($erreur) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Insertion du jeton CSRF -->
            <?= $csrfToken ?>

            <!-- Bouton de soumission du formulaire -->
            <button type="submit" class="btn btn-primary mt-5">S'inscrire</button>
        </fieldset>
    </form>
</div>

<?php
// Définition du titre de la page
$titre = "Inscription";

// Récupération du contenu mis en mémoire tampon
$content = ob_get_clean();

// Inclusion du template principal
require_once 'template.php';
?>