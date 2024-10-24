<?php

/**
 * Vue pour afficher la liste des livres ou un message si aucun livre n'est présent
 * 
 * Cette vue affiche soit une table contenant la liste des livres avec des options
 * pour modifier ou supprimer chaque livre, soit un message indiquant qu'aucun livre
 * n'a été ajouté, avec un bouton pour en ajouter un.
 * 
 * @var bool $pasDeLivre Indique s'il n'y a pas de livres à afficher
 * @var array $livresTab Tableau contenant les objets Livre à afficher
 * @var string SITE_URL Constante contenant l'URL de base du site
 */

ob_start() ?>

<?php if (!$pasDeLivre) : ?>
    <div class="d-flex flex-column justify-content-center">
        <?php
        // Inclut le fichier pour afficher les alertes
        require '../app/Views/showAlert.php';
        ?>
        <table class="table test-center">
            <!-- En-tête du tableau -->
            <tr class="table-dark">
                <th>Image</th>
                <th>Titre</th>
                <th>Nombre de pages</th>

                <th colspan="2">Actions</th>
            </tr>
            <?php
            // Boucle sur chaque livre du tableau
            foreach ($livresTab as $livre) : ?>
                <tr class="useurs table-active">
                    <!-- Affichage de l'image du livre -->
                    <td class="align-middle"><img
                            src="<?= SITE_URL ?>images/<?= $livre->getUrlImage(); ?>"
                            style="height: 60px;" alt="texte-alternatif"></td>
                    <!-- Affichage du titre du livre avec un lien vers sa page détaillée -->
                    <td class="align-middle">
                        <a href="<?= SITE_URL ?>livres/l/<?= $livre->getId() ?>"><?= $livre->getTitre() ?></a>
                    </td>
                    <!-- Affichage du nombre de pages -->
                    <td class="align-middle"><?= $livre->getNbreDePages(); ?></td>

                    <!-- Bouton pour modifier le livre -->
                    <td class="align-middle"><a
                            href="<?= SITE_URL ?>livres/m/<?= $livre->getId(); ?>"
                            class="btn btn-warning">Modifier</a>
                    </td>
                    <!-- Formulaire pour supprimer le livre avec une confirmation -->
                    <td class="align-middle">
                        <form method="post"
                            action="<?= SITE_URL ?>livres/s/<?= $livre->getId(); ?>"
                            onSubmit="return confirm('Voulez-vous vraiment supprimer le livre <?= $livre->getTitre(); ?> ?');">
                            <button class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <!-- Bouton pour ajouter un nouveau livre -->
        <a href="<?= SITE_URL ?>livres/a"
            class="btn btn-success d-block w-100">Ajouter</a>
    <?php else : ?>
        <!-- Affichage si aucun livre n'est présent -->
        <div class="d-flex flex-column">
            <div class="card text-white bg-info mb-3" style="max-width: 20rem;">
                <div class="card-header">Votre espace</div>
                <div class="card-body">
                    <h4 class="card-title">Désolé</h4>
                    <p class="card-text">Il semble que vous n'ayez pas encore uploader de livre dans votre espace.</p>
                    <p class="card-text">Pour y remédier, utilisez le bouton ci-dessous...</p>
                </div>
            </div>
            <!-- Bouton pour ajouter un nouveau livre -->
            <a href="<?= SITE_URL ?>livres/a"
                class="btn btn-success d-block w-100">Ajouter</a>
        </div>
    <?php endif; ?>
    </div>
    <?php
    // Définition du titre de la page
    $titre = "Livres";

    // Récupération du contenu mis en mémoire tampon
    $content = ob_get_clean();
    // Inclusion du template principal
    require_once 'template.php';
    ?>