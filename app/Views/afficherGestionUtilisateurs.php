<?php

/**
 * Vue pour afficher la gestion des utilisateurs
 * 
 * Cette vue permet d'afficher la liste des utilisateurs, de modifier leurs rôles,
 * de valider ou invalider leurs comptes, et de les supprimer.
 * 
 * @var array $utilisateurs Liste des objets Utilisateur
 * @var bool $pasDutilisateur Indique s'il n'y a pas d'utilisateurs
 */

ob_start(); // Démarre la temporisation de sortie

// Inclut les vues pour afficher les erreurs et les alertes
require '../app/Views/showErreurs.php';
require '../app/Views/showAlert.php';

// Vérifie s'il y a des utilisateurs à afficher
if (!$pasDutilisateur) : ?>
    <div class="d-flex flex-column justify-content-center">
        <?php require '../app/Views/showAlert.php'; // Inclut à nouveau la vue des alertes 
        ?>
        <table class="table test-center">
            <!-- En-tête du tableau -->
            <tr class="table-dark">
                <th>Id</th>
                <th>Identifiant</th>
                <th>email</th>
                <th>role</th>
                <th>valide</th>
                <th colspan="2">Actions</th>
            </tr>
            <?php
            // Boucle sur chaque utilisateur pour afficher ses informations
            foreach ($utilisateurs as $utilisateur) : ?>
                <tr class="useurs table-active">
                    <td class="align-middle"><?= $utilisateur->getIdUtilisateur(); ?></td>
                    <td class="align-middle"><?= $utilisateur->getIdentifiant(); ?></td>
                    <td class="align-middle"><?= $utilisateur->getEmail(); ?></td>
                    <form method="post" action="<?= SITE_URL ?>gestion-membres/m/<?= $utilisateur->getIdUtilisateur() ?>">
                        <input type="hidden" name='identifiant' value=<?= $utilisateur->getIdentifiant() ?>>
                        <td class="align-middle">
                            <!-- Liste déroulante pour modifier le rôle de l'utilisateur -->
                            <select name="role" class="form-select">
                                <option value="ROLE_USER" <?= $utilisateur->getRole() == 'ROLE_USER' ? 'selected' : ''; ?>>Utilisateur</option>
                                <option value="ROLE_ADMIN" <?= $utilisateur->getRole() == 'ROLE_ADMIN' ? 'selected' : ''; ?>>Administrateur</option>
                            </select>
                        </td>

                        <td class="align-middle">
                            <!-- Interrupteur pour valider/invalider le compte utilisateur -->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="isValide_<?= $utilisateur->getIdUtilisateur(); ?>" name="isValide" <?= $utilisateur->getIsValide() ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="isValide_<?= $utilisateur->getIdUtilisateur(); ?>">
                                    <?= $utilisateur->getIsValide() ? 'Validé' : 'Non validé'; ?>
                                </label>
                            </div>
                        </td>

                        <td class="align-middle">
                            <!-- Bouton pour soumettre les modifications -->
                            <button class="btn btn-warning">Modifier</button>
                        </td>
                    </form>
                    <td class="align-middle">
                        <!-- Formulaire pour supprimer l'utilisateur avec confirmation -->
                        <form method="post"
                            action="<?= SITE_URL ?>gestion-membres/s/<?= $utilisateur->getIdUtilisateur(); ?>"
                            onSubmit="return confirm('Voulez-vous vraiment supprimer l'utilisateur : <?= $utilisateur->getIdentifiant(); ?> ?');">
                            <button class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>

                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <!-- Affiche un message s'il n'y a pas d'utilisateurs -->
        <div class="d-flex flex-column">
            <div class="card text-white bg-info mb-3" style="max-width: 20rem;">
                <div class="alert alert-primary">Pas encore de membres</div>
            </div>
        <?php endif; ?>
        </div>
        <?php
        // Définit le titre de la page et récupère le contenu mis en mémoire tampon
        $titre = "Gestion utilisateurs";
        $content = ob_get_clean();
        // Inclut le template principal
        require_once 'template.php';
        ?>