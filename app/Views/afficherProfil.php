<?php

/**
 * Vue pour afficher et modifier le profil utilisateur
 *
 * Cette vue permet à l'utilisateur de voir et modifier ses informations de profil,
 * y compris son identifiant, son email et son mot de passe.
 *
 * @var object $utilisateur L'objet utilisateur contenant les informations du profil
 * @var string $csrfToken Le jeton CSRF pour la protection contre les attaques CSRF
 */

ob_start() ?>
<div class="d-flex flex-column justify-content-center">
    <?php
    // Inclure le fichier pour afficher les alertes
    require '../app/Views/showAlert.php';
    ?>

    <form class="m-auto w-50" method="post" action="<?= SITE_URL ?>profil/m/<?= $utilisateur->getIdUtilisateur() ?>">
        <fieldset>
            <!-- Champ pour l'identifiant -->
            <div class="form-group">
                <label for="identifiant" class="form-label mt-4">Identifiant : </label>
                <input type="text" name="identifiant" class="form-control" id="identifiant"
                    value="<?= isset($_SESSION['old_values']['identifiant']) ? htmlspecialchars($_SESSION['old_values']['identifiant']) : htmlspecialchars($utilisateur->getIdentifiant()) ?>" required>
                <?php
                // Afficher les erreurs pour l'identifiant s'il y en a
                if (isset($_SESSION['erreurs']['identifiant'])): ?>
                    <div class="text-danger">
                        <?php foreach ($_SESSION['erreurs']['identifiant'] as $erreur): ?>
                            <p><?= htmlspecialchars($erreur) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Champ pour l'email -->
            <div class="form-group">
                <label for="email" class="form-label mt-4">Email : </label>
                <input type="email" name="email" class="form-control" id="email"
                    value="<?= isset($_SESSION['old_values']['email']) ? htmlspecialchars($_SESSION['old_values']['email']) : htmlspecialchars($utilisateur->getEmail()) ?>" required>
                <?php
                // Afficher les erreurs pour l'email s'il y en a
                if (isset($_SESSION['erreurs']['email'])): ?>
                    <div class="text-danger">
                        <?php foreach ($_SESSION['erreurs']['email'] as $erreur): ?>
                            <p><?= htmlspecialchars($erreur) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Champ pour le nouveau mot de passe -->
            <div class="form-group">
                <label for="password" class="form-label mt-4">Mot de passe (laisser vide si inchangé) : </label>
                <input type="password" name="password" class="form-control" id="password"
                    placeholder="Votre nouveau mot de passe" autocomplete="off">
                <?php
                // Afficher les erreurs pour le mot de passe s'il y en a
                if (isset($_SESSION['erreurs']['password'])): ?>
                    <div class="text-danger">
                        <?php foreach ($_SESSION['erreurs']['password'] as $erreur): ?>
                            <p><?= htmlspecialchars($erreur) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Champ pour confirmer le nouveau mot de passe -->
            <div class="form-group">
                <label for="password_check" class="form-label mt-4">Confirmer le mot de passe : </label>
                <input type="password" name="password_check" class="form-control" id="password_check"
                    placeholder="Répétez votre nouveau mot de passe" autocomplete="off">
                <?php
                // Afficher les erreurs pour la confirmation du mot de passe s'il y en a
                if (isset($_SESSION['erreurs']['password_check'])): ?>
                    <div class="text-danger">
                        <?php foreach ($_SESSION['erreurs']['password_check'] as $erreur): ?>
                            <p><?= htmlspecialchars($erreur) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Jeton CSRF pour la protection contre les attaques CSRF -->
            <?= $csrfToken ?>

            <!-- Bouton de soumission du formulaire -->
            <button type="submit" class="btn btn-primary mt-5">Mettre à jour</button>
        </fieldset>
    </form>
</div>

<?php
// Définir le titre de la page et récupérer le contenu mis en mémoire tampon
$titre = "Modification Profil";
$content = ob_get_clean();

// Inclure le template principal
require_once 'template.php';
