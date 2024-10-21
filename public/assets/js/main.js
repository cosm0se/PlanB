/**
 * Gestion de la prévisualisation d'image
 *
 * Ce script permet de prévisualiser une image sélectionnée par l'utilisateur
 * avant son téléchargement.
 *
 * @author Votre Nom
 * @version 1.0
 */

// Sélection des éléments DOM
const preview = document.querySelector('#image-preview');
const input = document.querySelector('#image');

// Vérification de l'existence des éléments
if (input && preview) {
  // Ajout d'un écouteur d'événement sur le changement de fichier
  input.addEventListener('change', () => previewImage());

  /**
   * Fonction pour prévisualiser l'image sélectionnée
   */
  const previewImage = () => {
    // Récupération du fichier sélectionné
    const file = input.files[0];

    if (file) {
      // Création d'un nouveau FileReader
      const fileReader = new FileReader();

      // Définition de l'action à effectuer une fois le fichier chargé
      fileReader.onload = (e) => {
        // Mise à jour de l'attribut src de l'élément de prévisualisation
        preview.setAttribute('src', e.target.result);
      };

      // Lecture du fichier en tant que URL de données
      fileReader.readAsDataURL(file);
    }
  };
}
