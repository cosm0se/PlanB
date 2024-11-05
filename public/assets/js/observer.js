// Attendre que le DOM soit complètement chargé avant d'exécuter le code
document.addEventListener("DOMContentLoaded", () => {
  // Animation du titre au scroll
  const animationTitre = document.querySelector("h1"); // Sélectionne le titre principal (h1)

  // Définir les keyframes pour l'animation de mise à l'échelle
  const animeTitre = [
    { transform: "scale(0,0)" }, // Commence à une échelle de 0 (invisible)
    { transform: "scale(1,1)" }, // Termine à une échelle de 1 (visible)
  ];

  // Options pour l'animation
  const optionsTitre = {
    duration: 500, // Durée de l'animation en millisecondes
    fill: "forwards", // Laisse l'élément à sa position finale après l'animation
    iterations: 1, // L'animation ne se répète pas
  };

  // Fonction de callback pour l'IntersectionObserver pour le titre
  const isObserveTitre = (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        // Vérifie si le titre est visible
        animationTitre.animate(animeTitre, optionsTitre); // Joue l'animation si visible
      }
    });
  };

  // Création de l'IntersectionObserver pour le titre
  const observerTitre = new IntersectionObserver(isObserveTitre, {
    threshold: 0.5, // 50% de visibilité avant de déclencher l'animation
  });
  observerTitre.observe(animationTitre); // Commence à observer le titre

  // Animation pour les liens au survol
  const links = document.querySelectorAll("a.livre-link"); // Sélectionne tous les liens avec la classe 'livre-link'

  // Définir les keyframes pour l'animation de gigotement
  const shakeKeyframes = [
    { transform: "translateX(0)" }, // Position initiale
    { transform: "translateX(-5px)" }, // Déplacement à gauche
    { transform: "translateX(5px)" }, // Déplacement à droite
    { transform: "translateX(-5px)" }, // Retour à gauche
    { transform: "translateX(0)" }, // Retour à la position initiale
  ];

  // Options pour l'animation de gigotement
  const shakeOptions = {
    duration: 300, // Durée de l'animation en millisecondes
    iterations: 1, // Exécution unique de l'animation
  };

  // Ajoute un événement pour démarrer l'animation de gigotement au survol
  links.forEach((link) => {
    link.addEventListener("mouseenter", () => {
      link.animate(shakeKeyframes, shakeOptions); // Joue l'animation lorsque le lien est survolé
    });
  });

  // Sélectionne toutes les cartes de livres
  const livreCards = document.querySelectorAll(".livre-card");

  // Fonction de callback pour l'IntersectionObserver pour les cartes de livres
  const handleIntersection = (entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        // Vérifie si la carte de livre est visible
        entry.target.classList.add("visible"); // Ajoute une classe 'visible' si visible
        observer.unobserve(entry.target); // Arrête d'observer la carte une fois qu'elle est visible
      }
    });
  };

  // Création de l'IntersectionObserver pour les cartes de livres
  const observer = new IntersectionObserver(handleIntersection, {
    threshold: 0.5, // 50% de visibilité avant déclenchement
  });

  // Observer chaque carte de livre
  livreCards.forEach((card) => {
    observer.observe(card); // Commence à observer chaque carte de livre

    // Ajoute une animation de rotation et changement de couleur au survol
    card.addEventListener("mouseenter", () => {
      const titre = card.querySelector("h3.livre-title"); // Sélectionne le titre de la carte
      const livreBody = card.querySelector(".livre-body p"); // Sélectionne le paragraphe dans le body du livre

      // Définit les styles pour les animations
      card.style.transition =
        "transform 0.5s ease, background-color 0.5s ease, color 0.5s ease"; // Transition pour la carte
      titre.style.transition = "background-color 0.5s ease, color 0.5s ease"; // Transition pour le titre
      livreBody.style.transition = "color 0.5s ease"; // Transition pour le texte du livre

      // Change les couleurs au survol
      card.style.backgroundColor = "#3342fc"; // Couleur de fond de la carte
      titre.style.backgroundColor = "#3342fc"; // Couleur de fond du titre
      titre.style.color = "#fff"; // Couleur du texte du titre
      livreBody.style.color = "#fff"; // Couleur du texte dans le livre-body

      // Applique l'animation de rotation
      card.style.transform = "scale(1.05) rotateY(360deg)"; // Applique un zoom et une rotation
    });

    // Réinitialise les styles lorsque la souris quitte la carte
    card.addEventListener("mouseleave", () => {
      card.style.transform = "none"; // Réinitialise la transformation (rotation)
      card.style.backgroundColor = ""; // Réinitialise la couleur de fond

      const titre = card.querySelector("h3.livre-title"); // Sélectionne le titre de la carte
      const livreBody = card.querySelector(".livre-body p"); // Sélectionne le paragraphe dans le body du livre

      // Réinitialise les couleurs
      titre.style.backgroundColor = ""; // Réinitialise la couleur de fond du titre
      titre.style.color = ""; // Réinitialise la couleur du texte du titre
      livreBody.style.color = ""; // Réinitialise la couleur du texte dans le livre-body
    });
  });

  // Vérifie si l'URL correspond à la page d'un livre
  const currentUrl = window.location.href; // Récupère l'URL actuelle
  if (currentUrl.includes("/livres/l/")) {
    // Vérifie si l'URL contient '/livres/l/'
    // Applique une transition pour le changement de couleur de fond
    document.body.style.transition = "background-color 1s ease"; // Transition pour le fond du corps
    setTimeout(() => {
      document.body.style.backgroundColor = "#3342fc"; // Change la couleur de fond après 500ms
    }, 500);
  }
});
