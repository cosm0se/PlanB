<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Utilisateur;
use PDO;
use App\Service\AbstractConnexion;

/**
 * Classe UtilisateurRepository
 * 
 * Cette classe gère les opérations de base de données liées aux utilisateurs.
 * Elle étend la classe AbstractConnexion pour la gestion de la connexion à la base de données.
 */
class UtilisateurRepository extends AbstractConnexion
{
    private Utilisateur $utilisateur;

    /**
     * Récupère un utilisateur par son adresse e-mail
     *
     * @param string $email L'adresse e-mail de l'utilisateur
     * @return Utilisateur|false Retourne l'objet Utilisateur si trouvé, sinon false
     */
    public function getUtilisateurByEmail(string $email)
    {
        $req = "SELECT * FROM utilisateur WHERE email = ?";
        $stmt = $this->getConnexionBdd()->prepare($req);
        $stmt->execute([$email]);
        $utilisateurTab = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->CloseCursor();
        if (!$utilisateurTab) {
            return false;
        } else {
            $utilisateur = new Utilisateur($utilisateurTab['id_utilisateur'], $utilisateurTab['identifiant'], $utilisateurTab['password'], $utilisateurTab['email'], $utilisateurTab['role'], !$utilisateurTab['is_valide'] ? false : true);
            $this->setUtilisateur($utilisateur);
            return $this->getUtilisateur();
        }
    }

    /**
     * Modifie les informations d'un utilisateur dans la base de données
     *
     * @param int $idUtilisateur L'ID de l'utilisateur à modifier
     * @return Utilisateur|false Retourne l'objet Utilisateur mis à jour si réussi, sinon false
     */
    public function modifierUtilisateurBdd($idUtilisateur)
    {
        $req = "UPDATE utilisateur SET identifiant = :identifiant, email = :email";
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $req .= ", password = :password";
        }
        $req .= " WHERE id_utilisateur = :id_utilisateur;";
        $stmt = $this->getConnexionBdd()->prepare($req);
        $stmt->bindValue(":id_utilisateur", $idUtilisateur, PDO::PARAM_INT);
        $stmt->bindValue(":identifiant", $_POST['identifiant'], PDO::PARAM_STR);
        $stmt->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
        if (!empty($_POST['password'])) {
            $stmt->bindValue(":password", $password, PDO::PARAM_STR);
        }
        $resultat = $stmt->execute();
        $stmt->CloseCursor();
        if (!$resultat) {
            return false;
        }
        $utilisateur = $this->getUtilisateurByEmail($_POST['email']);
        return $utilisateur;
    }

    /**
     * Crée un nouvel utilisateur dans la base de données
     *
     * @param string $token Le token de validation pour le nouvel utilisateur
     * @return bool Retourne true si l'insertion a réussi, sinon false
     */
    public function setUtilisateurBdd($token)
    {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $req = "INSERT INTO utilisateur (identifiant, password, email, validation_token) VALUES(:identifiant, :password, :email, :validation_token);";
        $stmt = $this->getConnexionBdd()->prepare($req);
        $stmt->bindValue(":identifiant", $_POST['identifiant'], PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);
        $stmt->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
        $stmt->bindValue(":validation_token", $token, PDO::PARAM_STR);
        $resultat = $stmt->execute();
        $stmt->CloseCursor();
        if (!$resultat) {
            return false;
        }
        return true;
    }

    /**
     * Récupère un utilisateur par son token de validation
     *
     * @param string $token Le token de validation de l'utilisateur
     * @return Utilisateur|false Retourne l'objet Utilisateur si trouvé, sinon false
     */
    public function getUtilisateurByToken($token)
    {
        $req = "SELECT * FROM utilisateur WHERE validation_token = :token";
        $stmt = $this->getConnexionBdd()->prepare($req);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        $utilisateurTab = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->CloseCursor();
        if (!$utilisateurTab) {
            return false;
        } else {
            $utilisateur = new Utilisateur($utilisateurTab['id_utilisateur'], $utilisateurTab['identifiant'], $utilisateurTab['password'], $utilisateurTab['email'], $utilisateurTab['role'], !$utilisateurTab['is_valide'] ? false : true);
            $this->setUtilisateur($utilisateur);
            return $this->getUtilisateur();
        }
    }

    /**
     * Valide l'adresse e-mail d'un utilisateur
     *
     * @param int $idUtilisateur L'ID de l'utilisateur à valider
     * @return bool Retourne true si la validation a réussi, sinon false
     */
    public function validerEmailUtilisateur($idUtilisateur)
    {
        $req = "UPDATE utilisateur SET is_valide = 1, validation_token = NULL WHERE id_utilisateur = :id";
        $stmt = $this->getConnexionBdd()->prepare($req);
        $stmt->bindValue(':id', $idUtilisateur, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Récupère tous les utilisateurs non-administrateurs
     *
     * @return array Un tableau d'objets Utilisateur
     */
    public function getAllUtilisateurs(): array
    {
        $utilisateurs = [];
        $req = $this->getConnexionBdd()->prepare("SELECT * FROM utilisateur WHERE role != 'ROLE_ADMIN'");
        $req->execute();
        $utilisateur = $req->fetchALL(PDO::FETCH_ASSOC);
        $req->closeCursor();
        foreach ($utilisateur as $utilisateur) {
            $utilisateurEnCours = new Utilisateur($utilisateur['id_utilisateur'], $utilisateur['identifiant'], $utilisateur['password'], $utilisateur['email'], $utilisateur['role'], !$utilisateur['is_valide'] ? false : true);
            $utilisateurs[] = $utilisateurEnCours;
        }
        return $utilisateurs;
    }

    /**
     * Supprime un utilisateur de la base de données (par un administrateur)
     *
     * @param int $idUtilisateur L'ID de l'utilisateur à supprimer
     * @return bool Retourne true si la suppression a réussi, sinon false
     */
    public function supprimerUtilisateurByAdminInBdd($idUtilisateur)
    {
        $req = "UPDATE livre SET id_utilisateur = NULL WHERE id_utilisateur = :id_utilisateur";
        $stmt = $this->getConnexionBdd()->prepare($req);
        $stmt->bindValue(":id_utilisateur", $idUtilisateur, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->CloseCursor();
        $req = "DELETE FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
        $stmt = $this->getConnexionBdd()->prepare($req);
        $stmt->bindValue(":id_utilisateur", $idUtilisateur, PDO::PARAM_INT);
        $resultat = $stmt->execute();
        $stmt->CloseCursor();
        if (!$resultat) {
            return false;
        }
        return true;
    }

    /**
     * Modifie les informations d'un utilisateur par un administrateur
     *
     * @param int $idUtilisateur L'ID de l'utilisateur à modifier
     * @return bool Retourne true si la modification a réussi, sinon false
     */
    public function modifierUtilisateurByAdminInBdd($idUtilisateur)
    {
        $isValide = isset($_POST['isValide']) ? 1 : 0;
        $req = "UPDATE utilisateur SET role = :role, is_valide = :is_valide WHERE id_utilisateur = :id_utilisateur";
        $stmt = $this->getConnexionBdd()->prepare($req);
        $stmt->bindValue(":id_utilisateur", $idUtilisateur, PDO::PARAM_INT);
        $stmt->bindValue(":is_valide", $isValide, PDO::PARAM_INT);
        $stmt->bindValue(":role", $_POST['role'], PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
        $resultat = $stmt->execute();
        $stmt->CloseCursor();
        if (!$resultat) {
            return false;
        }
        return true;
    }

    /**
     * Récupère l'objet Utilisateur
     *
     * @return Utilisateur
     */
    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    /**
     * Définit l'objet Utilisateur
     *
     * @param Utilisateur $utilisateur L'objet Utilisateur à définir
     * @return self
     */
    public function setUtilisateur(Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }
}
