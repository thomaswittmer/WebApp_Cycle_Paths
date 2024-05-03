function openPopup() {
    document.getElementById("popup").style.display = "block";
}
function closePopup() {
    document.getElementById("popup").style.display = "none";
}



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