<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Classe Utilisateur
 * 
 * Cette classe représente un utilisateur dans le système.
 * Elle contient les informations de base d'un utilisateur telles que
 * son identifiant, son mot de passe, son email, son rôle et son statut de validation.
 */
class Utilisateur
{
    // Propriétés privées de la classe
    private int $id_utilisateur;
    private string $identifiant;
    private string $password;
    private string $email;
    private string $role;
    private bool $isValide;

    /**
     * Constructeur de la classe Utilisateur
     * 
     * @param int $id_utilisateur Identifiant unique de l'utilisateur
     * @param string $identifiant Nom d'utilisateur
     * @param string $password Mot de passe de l'utilisateur
     * @param string $email Adresse email de l'utilisateur
     * @param string $role Rôle de l'utilisateur dans le système
     * @param bool $isValide Statut de validation de l'utilisateur (par défaut : false)
     */
    public function __construct(
        int $id_utilisateur,
        string $identifiant,
        string $password,
        string $email,
        string $role,
        bool $isValide = false
    ) {
        $this->id_utilisateur =  $id_utilisateur;
        $this->identifiant = $identifiant;
        $this->password = $password;
        $this->email =  $email;
        $this->role = $role;
        $this->isValide = $isValide;
    }

    /**
     * Get the value of id_utilisateur
     *
     * @return int
     */
    public function getIdUtilisateur(): int
    {
        return $this->id_utilisateur;
    }

    /**
     * Set the value of id_utilisateur
     *
     * @param int $id_utilisateur
     *
     * @return self
     */
    public function setIdUtilisateur(int $id_utilisateur): self
    {
        $this->id_utilisateur = $id_utilisateur;
        return $this;
    }

    /**
     * Get the value of identifiant
     *
     * @return string
     */
    public function getIdentifiant(): string
    {
        return $this->identifiant;
    }

    /**
     * Set the value of identifiant
     *
     * @param string $identifiant
     *
     * @return self
     */
    public function setIdentifiant(string $identifiant): self
    {
        $this->identifiant = $identifiant;
        return $this;
    }

    /**
     * Get the value of password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get the value of email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get the value of role
     *
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @param string $role
     *
     * @return self
     */
    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Obtient la valeur de isValide
     *
     * @return bool Statut de validation de l'utilisateur
     */
    public function getIsValide(): bool
    {
        return $this->isValide;
    }

    /**
     * Définit la valeur de isValide
     *
     * @param bool $isValide Nouveau statut de validation
     *
     * @return self
     */
    public function setIsValide(bool $isValide): self
    {
        $this->isValide = $isValide;
        return $this;
    }
}
