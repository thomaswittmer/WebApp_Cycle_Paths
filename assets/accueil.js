function openPopup() {
    document.getElementById("popup").style.display = "block";
}
function closePopup() {
    document.getElementById("popup").style.display = "none";
}


// Fonction pour ouvrir la popup
function openPopup() {
    var popup = document.getElementById('popup');
    popup.style.display = 'block'; // Afficher la popup
}

// Lier l'événement de clic au lien
document.getElementById('infoButton').addEventListener('click', function(event) {
    event.preventDefault(); // Empêcher le comportement par défaut du lien
    openPopup(); // Appeler la fonction pour ouvrir la popup
});

// Fonction pour fermer la popup
function closePopup() {
    var popup = document.getElementById('popup');
    popup.style.display = 'none'; // Masquer la popup
}