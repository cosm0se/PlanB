<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Classe Livre
 * 
 * Cette classe représente un livre dans le système.
 * Elle contient les informations de base d'un livre comme son titre,
 * le nombre de pages, l'URL de l'image de couverture, etc.
 */
class Livre
{
    // Propriétés de la classe
    private int $id;
    private string $titre;
    private int $nbreDePages;
    private string $urlImage;
    private string $textAlternatif;
    private int|null $idUtilisateur;
    private string $uploader;

    /**
     * Constructeur de la classe Livre
     * 
     * @param int $id Identifiant unique du livre
     * @param string $titre Titre du livre
     * @param int $nbreDePages Nombre de pages du livre
     * @param string $urlImage URL de l'image de couverture du livre
     * @param string $textAlternatif Texte alternatif pour l'image de couverture
     * @param int|null $idUtilisateur Identifiant de l'utilisateur associé au livre (peut être null)
     * @param string $uploader Nom de l'utilisateur qui a uploadé le livre
     */
    public function __construct(
        int $id,
        string $titre,
        int $nbreDePages,
        string $urlImage,
        string $textAlternatif,
        int|null $idUtilisateur,
        string $uploader
    ) {
        $this->id = $id;
        $this->titre = $titre;
        $this->nbreDePages = $nbreDePages;
        $this->urlImage = $urlImage;
        $this->textAlternatif = $textAlternatif;
        $this->idUtilisateur = $idUtilisateur;
        $this->uploader = $uploader;
    }

    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of titre
     *
     * @return string
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * Set the value of titre
     *
     * @param string $titre
     *
     * @return self
     */
    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    /**
     * Get the value of nbreDePages
     *
     * @return int
     */
    public function getNbreDePages(): int
    {
        return $this->nbreDePages;
    }

    /**
     * Set the value of nbreDePages
     *
     * @param int $nbreDePages
     *
     * @return self
     */
    public function setNbreDePages(int $nbreDePages): self
    {
        $this->nbreDePages = $nbreDePages;
        return $this;
    }

    /**
     * Get the value of urlImage
     *
     * @return string
     */
    public function getUrlImage(): string
    {
        return $this->urlImage;
    }

    /**
     * Set the value of urlImage
     *
     * @param string $urlImage
     *
     * @return self
     */
    public function setUrlImage(string $urlImage): self
    {
        $this->urlImage = $urlImage;
        return $this;
    }

    /**
     * Get the value of textAlternatif
     *
     * @return string
     */
    public function getTextAlternatif(): string
    {
        return $this->textAlternatif;
    }

    /**
     * Set the value of textAlternatif
     *
     * @param string $textAlternatif
     *
     * @return self
     */
    public function setTextAlternatif(string $textAlternatif): self
    {
        $this->textAlternatif = $textAlternatif;
        return $this;
    }

    /**
     * Get the value of idUtilisateur
     *
     * @return int
     */
    public function getIdUtilisateur(): int|null
    {
        return $this->idUtilisateur;
    }

    /**
     * Set the value of idUtilisateur
     *
     * @param int|null $idUtilisateur
     *
     * @return self
     */
    public function setIdUtilisateur(int|null $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;
        return $this;
    }

    /**
     * Get the value of uploader
     *
     * @return string
     */
    public function getUploader(): string
    {
        return $this->uploader;
    }

    /**
     * Set the value of uploader
     *
     * @param string $uploader
     *
     * @return self
     */
    public function setUploader(string $uploader): self
    {
        $this->uploader = $uploader;
        return $this;
    }
}
