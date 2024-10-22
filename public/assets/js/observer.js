const animationTitre = document.querySelector("h1");

const animeTitre = [
  { transform: "translateX(-300px)" }, // Commence hors de l'écran (à gauche)
  { transform: "translateX(0%)" }, // S'arrête à sa position d'origine (au centre)
];

const optionsTitre = {
  duration: 1000, // Durée de l'animation (en millisecondes)
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

// Nouvelle animation pour les liens au survol
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
