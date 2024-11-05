<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Classe ValidationDonnees
 * 
 * Cette classe gère la validation des données selon des règles spécifiées.
 */
class ValidationDonnees
{
    /** @var array Stocke les erreurs de validation */
    private array $erreurs = [];

    /**
     * Valide les données selon les règles spécifiées
     *
     * @param array $regles Les règles de validation
     * @param array $datas Les données à valider
     * @return array Les erreurs de validation
     */
    public function valider(array $regles, array $datas)
    {
        foreach ($regles as $key => $regleTab) {
            if (array_key_exists($key, $datas)) {
                foreach ($regleTab as $regle) {
                    switch ($regle) {
                        case 'required':
                            $this->required($key, $datas[$key]);
                            break;
                        case substr($regle, 0, 5) === 'match':
                            $this->match($key, $datas[$key], $regle);
                            break;
                        case substr($regle, 0, 3) === 'min':
                            $this->min($key, $datas[$key], $regle);
                            break;
                    }
                }
            }
        }
        return $this->getErreurs();
    }

    /**
     * Vérifie si un champ est requis
     *
     * @param string $name Nom du champ
     * @param string|int|bool $data Valeur du champ
     */
    public function required(string $name, string|int|bool $data)
    {
        $value = trim($data);
        if (!isset($value) || empty($value) || is_null($value)) {
            $this->erreurs[$name][] = "Le champ {$name} est requis!";
        }
    }

    /**
     * Vérifie la longueur minimale d'un champ
     *
     * @param string $name Nom du champ
     * @param string $value Valeur du champ
     * @param string $regle Règle de validation (ex: 'min3')
     */
    private function min(string $name, string $value, string $regle): void
    {
        $limit = (int)substr($regle, 3);

        if (strlen($value) < $limit) {
            $this->erreurs[$name][] = "Le champ {$name} doit contenir un minimum de {$limit} caractères";
        }
    }

    /**
     * Vérifie si un champ correspond à un motif spécifique
     *
     * @param string $name Nom du champ
     * @param string|int|bool $data Valeur du champ
     * @param string $regle Règle de validation (ex: 'match/^[A-Z]/')
     */
    public function match(string $name, string|int|bool $data, string $regle)
    {
        $pattern = substr($regle, 6);
        if (!preg_match($pattern, $data)) {
            switch ($name) {
                case 'password':
                    $this->erreurs[$name][] = "Le mot de passe doit contenir minimum 8 caracteres, minimum 1 caractere special, une majuscule et 1 chiffre";
                    break;
                case 'identifiant':
                    $this->erreurs[$name][] = "L'identifiant ne correspond pas : pas d'espace, ni de caractères spéciaux";
                    break;
                case 'email':
                    $this->erreurs[$name][] = "L' adresse email n'est pas valide";
                    break;
                case 'titre':
                    $this->erreurs[$name][] = "Le champ {$name} doit commencer par une lettre majuscule, contenir minimum 3 lettres et maximum 50 lettres, espaces, chiffres et '-'(tiret du 6) autorisés";
                    break;
                case 'nbre-de-pages':
                    $this->erreurs[$name][] = "Le champ {$name} doit contenir uniquement des chiffres, [min: 1 - max: 10]";
                    break;
                case 'text-alternatif':
                    $this->erreurs[$name][] = "Le champ {$name} doit commencer par une lettre majuscule, contenir minimum 10 caractères et maximum 15à caracteres, espaces, '-'(tiret du 6), simple-quotes, double-quotes et point autorisés";
                    break;
            }
        }
    }

    /**
     * Récupère les erreurs de validation
     *
     * @return array Les erreurs de validation
     */
    public function getErreurs(): array
    {
        return $this->erreurs;
    }
}
