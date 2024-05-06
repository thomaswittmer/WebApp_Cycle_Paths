/* Fonctions permettant d'ouvrir et fermer la popup des fonctionnalités dans la page accueil */
function openPopup() {
    document.getElementById("popup").style.display = "block";
}
function closePopup() {
    document.getElementById("popup").style.display = "none";
}


/* Fonctions permettant d'ouvrir et fermer la popup des fonctionnalités dans la page de la carte */
function openPopup() {
    var popup = document.getElementById('popup');
    popup.style.display = 'block';
}

document.getElementById('infoButton').addEventListener('click', function(event) {
    event.preventDefault();
    openPopup();
});

function closePopup() {
    var popup = document.getElementById('popup');
    popup.style.display = 'none';
}