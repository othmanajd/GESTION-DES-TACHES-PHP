// Récupération de l'élément déclencheur (bouton)
const boutonAfficher = document.querySelector('.sub-1');

// Récupération de l'élément cible
const elementCible = document.querySelector('.cache');

// Ajout d'un écouteur d'événement sur le clic du bouton
boutonAfficher.addEventListener('click', function() {
  // Changement de la propriété "display" de l'élément cible pour l'afficher
    if(elementCible.style.display == 'block')
    {
        elementCible.style.display = 'none';
        boutonAfficher.style.transform='scaleX(1)';
    }
    else 
    {
        elementCible.style.display = 'block';
        boutonAfficher.style.transform='scaleX(-1)';
    }
});