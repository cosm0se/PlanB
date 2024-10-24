<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;
use App\Models\Livre;
use App\Service\AbstractConnexion;

/**
 * Classe LivresRepository
 * 
 * Cette classe gère les opérations liées aux livres dans la base de données.
 * Elle étend la classe AbstractConnexion pour la gestion de la connexion à la base de données.
 */
class LivresRepository extends AbstractConnexion
{
    /**
     * Tableau de livres
     *
     * @var array
     */
    private array $livres = [];

    /**
     * Ajoute un nouveau livre au tableau de livres
     *
     * @param object $nouveauLivre Le livre à ajouter
     * @return void
     */
    public function ajouterLivre(object $nouveauLivre)
    {
        $this->livres[] = $nouveauLivre;
    }

    /**
     * Charge tous les livres depuis la base de données
     *
     * @return array Tableau contenant tous les livres
     */
    public function chargementLivresBdd()
    {
        // Requête SQL pour récupérer tous les livres avec les informations de l'utilisateur
        $req = $this->getConnexionBdd()->prepare("SELECT id_livre, titre, nbre_de_pages, url_image, text_alternatif, description, l.id_utilisateur, identifiant FROM livre l LEFT JOIN utilisateur u ON l.id_utilisateur = u.id_utilisateur;");
        $req->execute();
        $livresImportes = $req->fetchALL(PDO::FETCH_ASSOC);
        $req->closeCursor();

        // Création des objets Livre et ajout au tableau
        foreach ($livresImportes as $livre) {
            $newLivre = new Livre($livre['id_livre'], $livre['titre'], $livre['nbre_de_pages'], $livre['url_image'], $livre['text_alternatif'], $livre['id_utilisateur'], $livre['identifiant'] !== null ? $livre['identifiant'] : "Pas d'uploader", $livre['description']);
            $this->ajouterLivre($newLivre);
        }
        return $this->getLivres();
    }

    /**
     * Récupère les livres d'un utilisateur spécifique
     *
     * @param int $idUtilisateur L'ID de l'utilisateur
     * @return array Tableau contenant les livres de l'utilisateur
     */
    public function getLivresByIdUtilisateur($idUtilisateur)
    {
        // Requête SQL pour récupérer les livres d'un utilisateur spécifique
        $req = $this->getConnexionBdd()->prepare("SELECT * FROM livre WHERE id_utilisateur = ?");
        $req->execute([$idUtilisateur]);
        $livresImportes = $req->fetchALL(PDO::FETCH_ASSOC);
        $req->closeCursor();

        // Création des objets Livre et ajout au tableau
        foreach ($livresImportes as $livre) {
            $uploader = isset($livre['identifiant']) ? $livre['identifiant'] : "Pas d'uploader";
            $newLivre = new Livre(
                $livre['id_livre'],
                $livre['titre'],
                $livre['nbre_de_pages'],
                $livre['url_image'],
                $livre['text_alternatif'],
                $livre['id_utilisateur'],
                $uploader,
                $livre['description']
            );
            $this->ajouterLivre($newLivre);
        }
        return $this->getLivres();
    }

    /**
     * Récupère un livre par son ID
     *
     * @param int $idLivre L'ID du livre à récupérer
     * @return Livre|null Le livre trouvé ou null si non trouvé
     */
    public function getLivreById($idLivre)
    {
        // Requête SQL pour récupérer un livre spécifique avec les informations de l'utilisateur
        $req = $this->getConnexionBdd()->prepare("SELECT l.id_livre, l.titre, l.nbre_de_pages, l.url_image, l.text_alternatif,l.description, l.id_utilisateur, u.identifiant FROM livre l LEFT JOIN utilisateur u ON l.id_utilisateur = u.id_utilisateur WHERE l.id_livre = ?");
        $req->execute([$idLivre]);
        $livresImportes = $req->fetchALL(PDO::FETCH_ASSOC);
        $req->closeCursor();

        // Création de l'objet Livre s'il est trouvé
        foreach ($livresImportes as $livre) {
            return new Livre($livre['id_livre'], $livre['titre'], $livre['nbre_de_pages'], $livre['url_image'], $livre['text_alternatif'], $livre['id_utilisateur'], $livre['identifiant'] !== null ? $livre['identifiant'] : "Pas d'uploader", $livre['description']);
            $this->ajouterLivre($newLivre);
        }
        return null;
    }

    /**
     * Ajoute un nouveau livre dans la base de données
     *
     * @param string $titre Le titre du livre
     * @param int $nbreDePages Le nombre de pages du livre
     * @param string $textAlternatif Le texte alternatif pour l'image du livre
     * @param string $nomImage Le nom de l'image du livre
     * @return void
     */
    public function ajouterLivreBdd(string $titre, int $nbreDePages, string $textAlternatif, string $nomImage, string $description)
    {
        $idUtilisateur = $_SESSION['utilisateur']['id_utilisateur'];
        // Requête SQL pour insérer un nouveau livre
        $req = "INSERT INTO livre (titre, nbre_de_pages, url_image, text_alternatif, id_utilisateur, description) VALUES 
            (:titre, :nbre_de_pages, :url_image, :text_alternatif, :id_utilisateur, :description)";
        $stmt = $this->getConnexionBdd()->prepare($req);
        $stmt->bindValue(":titre", $titre, PDO::PARAM_STR);
        $stmt->bindValue(":nbre_de_pages", $nbreDePages, PDO::PARAM_INT);
        $stmt->bindValue(":url_image", $nomImage, PDO::PARAM_STR);
        $stmt->bindValue(":text_alternatif", $textAlternatif, PDO::PARAM_STR);
        $stmt->bindValue(":id_utilisateur", $idUtilisateur, PDO::PARAM_INT);
        $stmt->bindValue(":description", $description, PDO::PARAM_STR); // Ajout de la description
        $stmt->execute();
        $stmt->closeCursor();
    }

    /**
     * Modifie un livre existant dans la base de données
     *
     * @param string $titre Le nouveau titre du livre
     * @param int $nbreDePages Le nouveau nombre de pages du livre
     * @param string $textAlternatif Le nouveau texte alternatif pour l'image du livre
     * @param string $nomImage Le nouveau nom de l'image du livre
     * @param string $description la nouvelle description
     * @param int $idLivre L'ID du livre à modifier
     * @return void
     */
    public function modificationLivreBdd(string $titre, int $nbreDePages, string $textAlternatif, string $description, string $nomImage, int $idLivre)
    {
        $idUtilisateur = $_SESSION['utilisateur']['role'] !== 'ROLE_ADMIN' ? $_SESSION['utilisateur']['id_utilisateur'] : $this->getLivreById($idLivre)->getIdUtilisateur();

        // Requête SQL pour mettre à jour un livre
        $req = "UPDATE livre SET titre = :titre, nbre_de_pages = :nbre_de_pages,  url_image = :url_image,text_alternatif = :text_alternatif, id_utilisateur = :id_utilisateur, description = :description WHERE id_livre = :id_livre";
        $stmt = $this->getConnexionBdd()->prepare($req);
        $stmt->bindValue(":id_livre", $idLivre, PDO::PARAM_INT);
        $stmt->bindValue(":titre", $titre, PDO::PARAM_STR);
        $stmt->bindValue(":nbre_de_pages", $nbreDePages, PDO::PARAM_INT);
        $stmt->bindValue(":url_image", $nomImage, PDO::PARAM_STR);
        $stmt->bindValue(":text_alternatif", $textAlternatif, PDO::PARAM_STR);
        $stmt->bindValue(":id_utilisateur", $idUtilisateur, PDO::PARAM_INT);
        $stmt->bindValue(":description", $description, PDO::PARAM_STR); // Ajout de la description
        $stmt->execute();
        $stmt->closeCursor();
    }

    /**
     * Supprime un livre de la base de données
     *
     * @param int $idLivre L'ID du livre à supprimer
     * @return void
     */
    public function supprimerLivreBdd($idLivre)
    {
        // Requête SQL pour supprimer un livre
        $req = "DELETE FROM livre WHERE id_livre = :id_livre";
        $stmt = $this->getConnexionBdd()->prepare($req);
        $stmt->bindValue(":id_livre", $idLivre, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    }

    /**
     * Récupère tous les livres
     *
     * @return array Tableau contenant tous les livres
     */
    public function getLivres(): array
    {
        return $this->livres;
    }

    /**
     * Définit le tableau de livres
     *
     * @param array $livres Le nouveau tableau de livres
     * @return self
     */
    public function setLivres(array $livres): self
    {
        $this->livres = $livres;
        return $this;
    }
}
