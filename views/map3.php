<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SAFELANE</title>
    <link rel="icon" type="image/png" href="/assets/images/icon_safelane_carre.png" sizes="32x32 64x64 128x128">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/vue@3.2.31"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="assets/map_style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.Default.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/leaflet.markercluster.js"></script>

    <script src="https://unpkg.com/esri-leaflet@3.0.10/dist/esri-leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.css" crossorigin="" />
    <script src="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.js" crossorigin=""></script>
    <style>
        /*page*/
body {
    margin: 0;
    padding: 0;
}

.logo-head {
    width: 40px;
    height: auto;
    position:absolute;
    top: 23px; 
    left: 75px;
    margin-left: 5px;
    align-items: center; 
    z-index: 1000;
}

header {
    background-color: #333;
    color: #fff;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

h1 {
    margin: 0;
    margin-left: 5px;
    font-family: 'Zen Dots';
    font-size: 32px;
}

.header-image {
    width: 90%;
    height: auto;
    margin-left: -10px;
}

/* bouton information pour lire les fonctionnalités */
.button-container-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-right: 30px;
    position: relative;
}

.bouton-info {
    display: block;
    width: 25px;
    height: auto;
    position: absolute;
    top: -13px;
    right: 30px;
}

ul {
    text-align: left;
    padding-left: 0;
    list-style-type: none;
}

ul li {
    font-family: Helvetica, sans-serif;
    font-size: 18px;
    line-height: 1.6;
    margin-bottom: 10px;
}

.popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1001;
    padding-top: 100px;
}

.popup-content {
    background-color: #fff;
    border: 3px solid #0F1A29;
    color: black;
    max-width: 80%;
    margin: 0 auto;
    padding: 20px;
    border-radius: 8px;
    position: relative;
}

.close {
    cursor: pointer;
    position: absolute;
    top: -10px;
    right: -10px;
    background: #DC3545;
    color: white;
    width: 30px;
    height: 30px;
    text-align: center;
    line-height: 30px;
    border-radius: 50%;
    box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
}

/* barre de recherche de l'adresse */
#research_bar {
    position:absolute;
    top: 25px; 
    left: 120px;
    margin-left: 5px;
    align-items: center;
    z-index: 1000;
}

#research_input[type="text"] {
    align-items: center;
    padding: 8px;
    border: none;
    border-radius: 5px;
    margin-right: 5px;
    font-size: 16px;
}

/*CARTE*/

#app {
    margin: 0;
    padding: 0;
    height: 100%;
    display: flex;
}

.carte {
    height: 100%;
    width: 100%;
    margin: 0;
    padding: 0;
}

/*CARTE*/
#map {
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}


/*statistique*/
#image-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
}

#image-overlay img {
    max-width: 100%;
    max-height: 60vh;
    width: auto;
    height: auto;
}

.close-button {
    cursor: pointer;
    position: absolute;
    top: -10px;
    right: -10px;
    background: #DC3545;
    color: white;
    width: 30px;
    height: 30px;
    text-align: center;
    line-height: 30px;
    border-radius: 50%;
    box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
}

/* BOUTON 3D*/
#cesiumContainer {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
}

/*GESTION TEMPORALITE*/
/*curseur temporel*/
.curseur-date {
    position: absolute;
    bottom: 20px;
    width: 90%;
    text-align: center;
    z-index: 1000;
    left: 50%;
    transform: translateX(-50%);
}

#dateSlider {
    width: 100%; 
    bottom : 10px;
    margin-bottom: 15px;
    z-index: 1000;
    border-radius: 10px;
}

/*point du curseur tentative logo*/

input[type="range"] {
    -webkit-appearance: none;
    appearance: none;
    width: 100%;
    height: 5px;
    background: #0F1A29;
}


input[type="range"]::-moz-range-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 50px;
    height: 50px;
    background: url('../assets/images/icon_safelane.png') center center no-repeat; 
    background-size: contain;
    cursor: pointer;
    border: none;
}

input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 50px;
    height: 50px;
    background: url('../assets/images/icon_safelane.png') center center no-repeat; 
    background-size: contain;
    cursor: pointer;
    border: none;
}



/*date séléctionée*/
#date {
    margin: auto;
    margin-top:15px;
    font-size: 15px;
    color: #0E0E29;
    padding: 5px;
    border-radius: 5px;
    width: 40%;
    background-color:rgba(255, 255, 255, 0.5);
}

/*checkbox*/
.checkbox-date {
    position: absolute;
    bottom: 20px;
    right: 10px;
    z-index: 1001;
    background-color: white;
    margin-left:5px;
    padding: 5px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
}

.checkbox-date input[type="checkbox"] {
    width: 20px;
    height: 20px;
    margin-right: 10px;
}


.checkbox-mois {
    position: absolute;
    bottom: 20px;
    right: 150px;
    z-index: 1001;
    background-color: white;
    padding: 5px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
}

.checkbox-mois label {
    font-size: 20px;
}

.checkbox-mois input[type="checkbox"] {
    width: 20px;
    height: 20px;
    margin-right: 10px;
}


/*eclaircie quand inactif*/
.anDesactive {
    color: #6c757d;
}

#plan{
    width:50%;
}

/*BANDEAU LATERAL*/
.offcanvas-header {
    height: 10%;
    position: relative;
}

.btn-lateral { 
    top: 20px; 
    left: 15px;
    position:absolute;
    z-index: 1000;
}


.offcanvas-start {
    width: 100%; 
    height: 100%; 
    left: -30%;  
}


#barre-laterale{
    display: flex;
    flex-direction: column;
    height: 100%; 
    width:100%;
}

.btn-group {
    width: 50%;
    margin-bottom: 5px;
}

/*gestion des caractéristiques*/
.form-switch img {
    height: 40px;
    width: auto;
    margin-right: 10px;
}

.form-switch {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.form-switch input[type="checkbox"] {
    margin-left: 10px;
}

.form-switch .gauche {
    margin-right: 10px;
}

.form-switch .droite {
    margin-left: auto;
}

.dropdown-menu {
    min-width: auto !important;
    width: max-content !important;
    z-index: 1000;
}

.dropdown-item {
    cursor: pointer;
}

/* eclaircie bouton plan velo*/
.clique {
    opacity: 0.5;
}

/*legende*/
.legend {
    top: 10px;
    right: 10px;
    background-color: white;
    border: 1px solid #ccc;
    z-index: 1000;
}

.legend-color {
    display: inline-block;
    width: 20px;
    height: 20px;
    margin-right: 5px;
    vertical-align: middle;
}

#legend {
    margin-top: 10px;
    padding-top: 10px;
}

/* Styles CSS pour les images de légende */
.legend-img {
    width: 30px;
    height: 30px;
    margin-right: 5px;
}

#legendAcci {
    margin-top: 20px;
}

/*bouton fonds de carte*/
#titre-carte, #titre-pistes{
    border-top: 2px solid #ccc; 
    margin-top: 25px;
    padding-top: 25px;
}

.button-container-fond {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 20px;
    margin-bottom: 5px;
}

.button-row {
    display: flex;
    justify-content: space-around;
    align-items: center;
    width: 100%;
    margin-bottom: 10px;
}

.map-button {
    position: relative;
    border: none;
    background-color: #f0f0f0;
    padding: 0;
    width: 150px;
    height: 90px;
    display: flex;
    border-radius: 10px;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: 2px solid #0F1A29;
}

.map-button:last-child {
    margin-right: 0;
}

.map-button img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
}

.button-label {
    position: absolute;
    bottom: 10px;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    color: #0F1A29;
    text-shadow: -1px -1px 0 white, 1px -1px 0 white, -1px 1px 0 white, 1px 1px 0 white;
}

.button-label-sat {
    position: absolute;
    bottom: 10px;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    color: white;
    text-shadow: -1px -1px 0 #0F1A29, 1px -1px 0 #0F1A29, -1px 1px 0 #0F1A29, 1px 1px 0 #0F1A29;
}

/* boutons pour le play/pause/stop de la lecture automatique du curseur temporel */
.button-container {
    position: absolute;
    bottom: 20px;
    left: 10px;
    display: flex;
    align-items: center;
    z-index: 1000;
}

.play-button, .stop-button, .pause-button {
    border: none;
    border-radius: 20px;
    padding: 5px;
    margin-right: 5px;
    left: 35%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.play-button {
    background-color: #0F1A29;
    border: 2px solid #0F1A29;
}

.stop-button {
    background-color: #eb3d3d;
    border: 2px solid #eb3d3d;
}

.pause-button {
    background-color: #e4f319e9;
    border: 2px solid #e4f319e9;
}

.play-button img, .stop-button img, .pause-button img {
    width: 20px;
    height: 20px;
    vertical-align: middle;
}

.play-button:hover, .stop-button:hover, .pause-button:hover {
    transform: scale(1.1);
}

.meteo-decale-vers-le-bas {
    margin-top: 260px;
    transition: margin-top 0.3s ease;
}

.carac-decale-vers-le-bas {
    margin-top: 420px;
    transition: margin-top 0.3s ease;
}



</style>

</head>

<body>
    <div id=app>
        <div class="carte">
            <!--<div id="cesiumContainer"></div> -->
            <!-- curseur temporel -->
            <div id="map">
                <a href="/"><img src="/assets/images/safelane_carre.png" alt="logo" class="logo-head"></a>
                <!-- barre recherche-->
                <div id="research_bar">
                    <input class="form-control me-2" type="search" id="research_input" name="pacViewPlace" placeholder="Entrez un lieu..." aria-label="Search">
                    <ul id="suggestions" class="dropdown-menu" style="display: none;"></ul>
                </div>

                <!-- fermer les fenêtres des statistiques -->
                <div id="image-overlay">
                    <img id="overlayImage" src="" alt="Overlay Image">
                    <span class="close-button" onclick="closeImageOverlay()">X</span>
                </div>

                <!-- voir toutes les dates -->
                <div class="checkbox-date">
                    <input class="form-check-input mr-2" type="checkbox" v-model="caseChecked" id="checkboxdate" :disabled="caseDisabled" @change="annule_annee">
                    <span :class="{ 'anDesactive': caseDisabled }"> Toutes les années </span>
                </div>

                <!-- coche pour avoir un curseur selon mois et année -->
                <div class="checkbox-mois">
                    <input class="form-check-input mr-2" type="checkbox" v-model="moisChecked" @change="annule_annee">  
                    <span> Curseur par mois </span>
                </div>

                <!-- Bouton play pour la lecture automatique-->
                <div class="button-container">
                    <button v-if="!isAutoPlaying" @click="startAutoPlay()" class="play-button">
                        <img src="assets/images/play.png" alt="Lecture automatique">
                    </button>
                    <button v-if="isAutoPlaying" @click="stopAutoPlay()" class="stop-button">
                        <img src="assets/images/stop.svg" alt="Arrêter la lecture automatique">
                    </button>
                    <button v-if="isAutoPlaying" @click="pauseAutoPlay()" class="pause-button">
                        <img src="assets/images/pause.svg" alt="Pause">
                    </button>
                </div>

                <!-- curseur temporel -->
                <div id="dateSlider">
                    <div v-if="moisChecked" class="curseur-date">
                        <input type="range" min="0" max="83" v-model="selectedMonth"  @change="cherche_mois_annee">
                        <p id="date"><strong>Date sélectionnée : {{ formattedDate }}</strong></p>
                    </div>
                    <div v-if="!moisChecked" class="curseur-date">
                        <input type="range" min="2016" max="2022" v-model="selectedYear"  @change="cherche_annee">
                        <p id="date"><strong>Date sélectionnée : {{ selectedYear }}</strong></p>
                    </div>
                </div>

            </div><!--map-->

            <button class="btn btn-secondary btn-lateral" id="btn-lateral" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
                <div class="offcanvas-header">
                    <a href="/"><img src="/assets/images/param_safelane.png" alt="logo" class="header-image"></a>
                    <a id="infoButton"><img src="/assets/images/bouton_info.png" alt="info" class="bouton-info"></a>
                    <button type="button" class="btn-close" id="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div id="popup" class="popup">
                    <div class="popup-content">
                        <span class="close" onclick="closePopup()">X</span>
                        <h2>Fonctionnalités de SAFELANE</h2>
                        <p>Voici les principales fonctionnalités de l'application :</p>
                        <ul>
                            <li>⚠️ Identification des zones à risque pour les cyclistes.</li>
                            <li>🚲 Consultation du Plan Vélo 2021-2026 de la mairie de Paris.</li>
                            <li>🗓️ Visualisation des données des accidents par année.</li>
                            <li>🎥 Carte interactive 2D avec option 3D pour une visualisation plus détaillée du lieu de l'accident.</li>
                            <li>✅ Filtrage des accidents par caractéristiques (météo, infrastructure, luminosité, ...).</li>
                            <li>🗺️ Personnalisation du fond de carte.</li>
                            <li>📈 Affichage de statistiques sur les accidents.</li>
                        </ul>
                    </div>
                </div>

                <div class="offcanvas-body">

                    <div id="barre-laterale">

                        <!-- LUMINOSITE -->
                        <div class="boutons-barre">
                            <div class="btn-group lumi">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="false">
                                    Luminosité
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <form>
                                        <div class="form-switch lum mx-2">
                                            <div class="gauche">
                                                Visualiser sur la carte
                                            </div>
                                            <div class="droite all">
                                                <input class="form-check-input mr-2" type="checkbox" value="lum">
                                            </div>
                                        </div>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <div class="form-switch lum mx-2">
                                            <div class="gauche">
                                                <img src="../assets/images/icones/lum/Plein jour.png" alt="Plein jour">
                                                Plein jour
                                            </div>
                                            <div class="droite">
                                                <input class="form-check-input mr-2" type="checkbox" value="Plein jour" checked>
                                            </div>
                                        </div>
                                        <div class="form-switch lum mx-2">
                                            <div class="gauche">
                                                <img src="../assets/images/icones/lum/Crépuscule ou aube.png" alt="Crépuscule ou aube">
                                                Crépuscule ou aube
                                            </div>
                                            <div class="droite">
                                                <input class="form-check-input mr-2" type="checkbox" value="Crépuscule ou aube" checked>
                                            </div>
                                        </div>
                                        <div class="form-switch lum mx-2">
                                            <div class="gauche">
                                                <img src="../assets/images/icones/lum/Nuit sans éclairage public.png" alt="Nuit sans éclairage public">
                                                Nuit sans éclairage public
                                            </div>
                                            <div class="droite">
                                                <input class="form-check-input mr-2" type="checkbox" value="Nuit sans éclairage public" checked>
                                            </div>
                                        </div>
                                        <div class="form-switch lum mx-2">
                                            <div class="gauche">
                                                <img src="../assets/images/icones/lum/Nuit avec éclairage public non allumé.png" alt="Nuit avec éclairage public non allumé">
                                                Nuit avec éclairage public non allumé
                                            </div>
                                            <div class="droite">
                                                <input class="form-check-input mr-2" type="checkbox" value="Nuit avec éclairage public non allumé" checked>
                                            </div>
                                        </div>
                                        <div class="form-switch lum mx-2">
                                            <div class="gauche">
                                                <img src="../assets/images/icones/lum/Nuit avec éclairage public allumé.png" alt="Nuit avec éclairage public allumé">
                                                Nuit avec éclairage public allumé
                                            </div>
                                            <div class="droite">
                                                <input class="form-check-input mr-2" type="checkbox" value="Nuit avec éclairage public allumé" checked>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- METEO -->
                        <div class="btn-group meteo">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="false">
                                Météo
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <form>
                                    <div class="form-switch atm mx-2">
                                        <div class="gauche">
                                            Visualiser sur la carte
                                        </div>
                                        <div class="droite all">
                                            <input class="form-check-input mr-2" type="checkbox" value="atm">
                                        </div>
                                    </div>
                                    <div class="form-switch atm mx-2">
                                        <div class="gauche">
                                            <img src="../assets/images/icones/atm/Temps éblouissant.png" alt="Temps éblouissant">
                                            Temps éblouissant
                                        </div>
                                        <div class="droite">
                                            <input class="form-check-input mr-2" type="checkbox" value="Temps éblouissant" checked>
                                        </div>
                                    </div>
                                    <div class="form-switch atm mx-2">
                                        <div class="gauche">
                                            <img src="../assets/images/icones/atm/Brouillard - fumée.png" alt="Brouillard - fumée">
                                            Brouillard - fumée
                                        </div>
                                        <div class="droite">
                                            <input class="form-check-input mr-2" type="checkbox" value="Brouillard - fumée" checked>
                                        </div>
                                    </div>
                                    <div class="form-switch atm mx-2">
                                        <div class="gauche">
                                            <img src="../assets/images/icones/atm/Neige - grêle.png" alt="Neige - grêle">
                                            Neige - grêle
                                        </div>
                                        <div class="droite">
                                            <input class="form-check-input mr-2" type="checkbox" value="Neige - grêle" checked>
                                        </div>
                                    </div>
                                    <div class="form-switch atm mx-2">
                                        <div class="gauche">
                                            <img src="../assets/images/icones/atm/Vent fort - tempête.png" alt="Vent fort - tempête">
                                            Vent fort - tempête
                                        </div>
                                        <div class="droite">
                                            <input class="form-check-input mr-2" type="checkbox" value="Vent fort - tempête" checked>
                                        </div>
                                    </div>
                                    <div class="form-switch atm mx-2">
                                        <div class="gauche">
                                            <img src="../assets/images/icones/atm/Pluie légère.png" alt="Pluie légère">
                                            Pluie légère
                                        </div>
                                        <div class="droite">
                                            <input class="form-check-input mr-2" type="checkbox" value="Pluie légère" checked>
                                        </div>
                                    </div>
                                    <div class="form-switch atm mx-2">
                                        <div class="gauche">
                                            <img src="../assets/images/icones/atm/Pluie forte.png" alt="Pluie forte">
                                            Pluie forte
                                        </div>
                                        <div class="droite">
                                            <input class="form-check-input mr-2" type="checkbox" value="Pluie forte" checked>
                                        </div>
                                    </div>
                                    <div class="form-switch atm mx-2">
                                        <div class="gauche">
                                            <img src="../assets/images/icones/atm/Temps couvert.png" alt="Temps couvert">
                                            Temps couvert
                                        </div>
                                        <div class="droite">
                                            <input class="form-check-input mr-2" type="checkbox" value="Temps couvert" checked>
                                        </div>
                                    </div>
                                    <div class="form-switch atm mx-2">
                                        <div class="gauche">
                                            <img src="../assets/images/icones/atm/Normale.png" alt="Normale">
                                            Normale
                                        </div>
                                        <div class="droite">
                                            <input class="form-check-input mr-2" type="checkbox" value="Normale" checked>
                                        </div>
                                    </div>
                                    <div class="form-switch atm mx-2">
                                        <div class="gauche">
                                            <img src="../assets/images/icones/atm/Autre.png" alt="Autre">
                                            Autre
                                        </div>
                                        <div class="droite">
                                            <input class="form-check-input mr-2" type="checkbox" value="Autre" checked>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- CARACTERISTIQUES -->
                        <div class="btn-group carac contenu-decalable">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Caractéristiques
                            </button>
                            <div class="dropdown-menu">
                                <form>
                                    <option class="dropdown-item caractere" value="int">Type d'intersection</option>
                                    <option class="dropdown-item caractere" value="col">Type de collision</option>
                                    <option class="dropdown-item caractere" value="surf">Etat de la route</option>
                                    <option class="dropdown-item caractere" value="infra">Infrastructure de la route</option>
                                    <option class="dropdown-item caractere" value="catv">Catégorie du véhicule</option>
                                </form>
                            </div>
                        </div>

                        <!-- STATISTIQUES-->
                        <div class="btn-group">
                            <button id="stat" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Statistiques
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" onclick="showImageOverlay('assets/images/categorie_velo.png')">Catégorie du vélo</a></li>
                                <li><a class="dropdown-item" onclick="showImageOverlay('assets/images/type_intersection.png')">Type d'intersection</a></li>
                                <li><a class="dropdown-item" onclick="showImageOverlay('assets/images/type_surface.png')">Type de surface</a></li>
                                <li><a class="dropdown-item" onclick="showImageOverlay('assets/images/type_luminosite.png')">Type de luminosité</a></li>
                                <li><a class="dropdown-item" onclick="showImageOverlay('assets/images/type_collision.png')">Type de collision</a></li>
                            </ul>
                        </div>

                        <!-- COUCHES PISTES -->
                        <h3 id="titre-pistes">Couches voies cyclables</h3>

                        <button type="button" class="btn btn-primary" id="plan">Plan Vélo 2024</button>

                        <div id="legend">
                            <h4>Légende</h4>
                            <div><span class="legend-color" style="background-color: #1D3FD9;"></span> piste cyclable</div>
                            <div><span class="legend-color" style="background-color: #63DE6E;"></span> voie verte / aménagement mixte</div>
                            <div><span class="legend-color" style="background-color: #EC1DD0;"></span> couloir bus + vélo</div>
                            <div><span class="legend-color" style="background-color: #4DC0EF;"></span> bande cyclable</div>
                            <div><span class="legend-color" style="background-color: #C1A4BD ;"></span> voie mixte</div>
                        </div>

                        <div id="legendAcci"></div>

                        <!-- FOND DE CARTE -->
                        <h3 id="titre-carte">Fond de carte</h3>
                        <div class="button-container-fond">
                            <!-- Première ligne de boutons -->
                            <div class="button-row">
                                <button id="btnSatellite" class="map-button">
                                    <img src="assets/images/fond_aerien_paris.png" alt="Vue satellite">
                                    <span class="button-label-sat">Vue satellite</span>
                                </button>
                                <button id="btnTopographic" class="map-button">
                                    <img src="assets/images/fond_topo_paris.png" alt="Vue topographique">
                                    <span class="button-label">Vue topologique</span>
                                </button>
                            </div>
                            <!-- Deuxième ligne de boutons -->
                            <div class="button-row">
                                <button id="btnOpenStreetMap" class="map-button">
                                    <img src="assets/images/fond_routier_paris.png" alt="Vue OpenStreetMap">
                                    <span class="button-label">Vue OpenStreetMap</span>
                                </button>
                                <button id="btnDefault" class="map-button">
                                    <img src="assets/images/fond_gris_clair.png" alt="Vue routière">
                                    <span class="button-label">Vue base gris clair</span>
                                </button>
                            </div>
                        </div>


                        <!-- AFFICHAGE ACCIDENTS -->
                        <h3 id="titre-carte">Affichage des accidents</h3>
                        <div class="button-container-fond">
                            <form>
                                <div class="form-switch atm mx-2">
                                    <div class="gauche">
                                        <img src="../assets/images/accident.png" alt="Accidents">
                                        Masquer les accidents
                                    </div>
                                    <div class="droite">
                                        <input id="accidentCheckbox" class="form-check-input mr-2" type="checkbox" value="Accidents" >
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- AFFICHAGE CLUSTERS -->
                        <div class="button-container-fond">
                            <form>
                                <div class="form-switch atm mx-2">
                                    <div class="gauche">
                                        <img src="../assets/images/cluster.png" alt="Clusters">
                                        Masquer les clusters
                                    </div>
                                    <div class="droite">
                                        <input id="clusterCheckbox" class="form-check-input mr-2" type="checkbox" value="Clusters" >
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> <!--barre-laterale-->
                </div>
            </div>
        </div><!--carte-->
    </div><!--app-->

    <script src="/assets/map.js"></script>
    <script src="/assets/accueil.js"></script>
    <!-- <script src="/assets/leaflet.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

<!-- clef Gabin : AIzaSyCV613JJHOSp-JVbKMB7P8sxJlSt_wrK80 -->
<!-- clef Thomas : AIzaSyAuosDPx4wvSs6L__ZM1AtcJLjTaGq2P7w -->