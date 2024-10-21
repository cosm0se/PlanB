<?php

declare(strict_types=1);

namespace App\Service;

use Exception;

/**
 * Classe utilitaire pour diverses opérations
 */
class Utils
{
    /**
     * Ajoute une image téléchargée dans un répertoire spécifié
     *
     * @param array $image Tableau contenant les informations de l'image téléchargée
     * @param string $repertoire Chemin du répertoire où l'image sera stockée
     * @return string|null Nom du fichier de l'image ajoutée ou null en cas d'erreur
     */
    public static function ajoutImage($image, $repertoire)
    {
        // Vérifier si une image a été téléchargée
        if ($image['size'] === 0) {
            return $_SESSION['erreurs']['image'][] = 'Vous devez uploader une image';
        }

        // Créer le répertoire de destination s'il n'existe pas
        if (!file_exists($repertoire)) {
            mkdir($repertoire, 0777);
        }

        // Vérifier si le fichier temporaire existe
        if (empty($image['tmp_name'])) {
            return $_SESSION['erreurs']['image'][] = 'Vous devez uploader une image';
        }

        // Vérifier l'extension du fichier
        $extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        $extensionsTab = ['png', 'webp', 'jpg'];

        if (!in_array($extension, $extensionsTab)) {
            return $_SESSION['erreurs']['image'][] = "Extension non autorisée => ['png', 'webp', 'jpg']";
        }

        // Vérifier la taille du fichier (max 400 Mo)
        if ($image['size'] > 400000000) { // 400MO
            return $_SESSION['erreurs']['image'][] = "Fichier trop volumineux : max 400MO";
        }

        // Générer un nom de fichier unique
        $filename = uniqid() . "-" . $image['name'];
        $target = $repertoire . $filename;

        // Déplacer le fichier téléchargé vers le répertoire de destination
        if (!move_uploaded_file($image['tmp_name'], $target)) {
            return $_SESSION['erreurs']['image'][] = "Le transfert de l'image a échoué";
        } else {
            return $filename;
        }
    }
}
