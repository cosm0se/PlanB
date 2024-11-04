const animationTitre = document.querySelector("h1");

const animeTitre = [
  { transform: "scale(0,0)" }, // Commence a 0
  { transform: "scale(1,1)" }, // fini a sa taille finale
];

const optionsTitre = {
  duration: 500, // Durée de l'animation (ms)
  fill: "forwards", // Laisse l'élément à sa position finale après l'animation
  iterations: 1, // L'animation ne se répète pas
};

const isObserveTitre = (entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      animationTitre.animate(animeTitre, optionsTitre);
    }
  });
};

const observerTitre = new IntersectionObserver(isObserveTitre, {
  threshold: 0.5,
});
observerTitre.observe(animationTitre);

// animation pour les liens au survol
const links = document.querySelectorAll("a.livre-link");

links.forEach((link) => {
  // Animation de gigotement (oscillation sur l'axe X)
  const shakeKeyframes = [
    { transform: "translateX(0)" },
    { transform: "translateX(-5px)" },
    { transform: "translateX(5px)" },
    { transform: "translateX(-5px)" },
    { transform: "translateX(0)" },
  ];

  const shakeOptions = {
    duration: 300, // Temps d'exécution de l'animation (300ms pour un effet rapide)
    iterations: 1, // Exécution unique (on pourra la rejouer au survol)
  };

  // Ajoute l'événement pour démarrer l'animation au survol
  link.addEventListener("mouseenter", () => {
    link.animate(shakeKeyframes, shakeOptions);
  });
});

// Sélectionne toutes les cartes de livres
const livreCards = document.querySelectorAll(".livre-card");

// Fonction de callback pour IntersectionObserver
const handleIntersection = (entries, observer) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      // Ajoute la classe visible lorsqu'elle est à 50% visible
      entry.target.classList.add("visible");
      // Optionnel: arrêter d'observer l'élément après qu'il soit visible
      observer.unobserve(entry.target);
    }
  });
};

// Création de l'IntersectionObserver
const observer = new IntersectionObserver(handleIntersection, {
  threshold: 0.5, // 50% de visibilité avant déclenchement
});

// Observer chaque carte de livre
livreCards.forEach((card) => {
  observer.observe(card);
});

document.addEventListener("DOMContentLoaded", function () {
  // Récupère l'URL actuelle avec const
  const currentUrl = window.location.href;

  // Vérifie si l'URL correspond à la page d'un livre
  if (currentUrl.includes("/livres/l/")) {
    // Applique une transition pour le changement de couleur de fond
    document.body.style.transition = "background-color 1s ease";

    // Utilise setTimeout pour attendre 500ms avant de changer la couleur
    setTimeout(function () {
      document.body.style.backgroundColor = "#fff";
    }, 500);
  }
});
