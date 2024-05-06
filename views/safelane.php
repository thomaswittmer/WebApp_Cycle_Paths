<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SAFELANE</title>
    <link rel="icon" type="image/png" href="/assets/images/logo/icon_safelane_carre.png" sizes="32x32 64x64 128x128">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/vue@3.2.31"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="assets/safelane_style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.Default.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/leaflet.markercluster.js"></script>

    <script src="https://unpkg.com/esri-leaflet@3.0.10/dist/esri-leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.css" crossorigin="" />
    <script src="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.js" crossorigin=""></script>
</head>

<body>
    <div id=app>
        <div class="carte">
            <!-- Afficher fonctionnalités présentes sur la carte -->
            <div id="map">
                <a href="/"><img src="/assets/images/logo/safelane_carre.png" alt="logo" class="logo-head"></a>
                <!-- Créer la boîte pour la barre  de recherche-->
                <div id="research_bar">
                    <input class="form-control me-2" type="search" id="research_input" name="pacViewPlace" placeholder="Entrez un lieu..." aria-label="Search">
                    <ul id="suggestions" class="dropdown-menu" style="display: none;"></ul>
                </div>
                <!-- Fermer les fenêtres des statistiques -->
                <div id="image-overlay">
                    <img id="overlayImage" src="" alt="Overlay Image">
                    <span class="close-button" onclick="closeImageOverlay()">X</span>
                </div>
                <!-- Checkbox pour voir visualiser les accidents de 2016 à 2022 -->
                <div class="checkbox-date">
                    <input class="form-check-input mr-2" type="checkbox" v-model="caseChecked" id="checkboxdate" :disabled="caseDisabled" @change="annule_annee">
                    <span :class="{ 'anDesactive': caseDisabled }"> Toutes les années </span>
                </div>
                <!-- Checkbox pour visualiser les accidents selon le mois et l'année -->
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
                    <!-- Ajouter des boutons de contrôle de vitesse -->
                    <button @click="setAutoPlaySpeed(1)" :disabled="!isAutoPlaying" class="speed-button">x1</button>
                    <button @click="setAutoPlaySpeed(2)" :disabled="!isAutoPlaying" class="speed-button">x2</button>
                    <button @click="setAutoPlaySpeed(0.5)" :disabled="!isAutoPlaying" class="speed-button">x0.5</button>
                </div>
                <!-- Créer un curseur temporel -->
                <div id="dateSlider">
                    <div v-if="moisChecked" class="curseur-date">
                        <input type="range" min="0" max="83" v-model="selectedMonth" @change="cherche_mois_annee">
                        <p id="date"><strong>Date sélectionnée : {{ formattedDate }}</strong></p>
                    </div>
                    <div v-if="!moisChecked" class="curseur-date">
                        <input type="range" min="2016" max="2022" v-model="selectedYear" @change="cherche_annee">
                        <p id="date"><strong>Date sélectionnée : {{ selectedYear }}</strong></p>
                    </div>
                </div>
            </div><!--map-->

            <!-- Créer le bandeau latéral contenant des paramètres -->
            <!-- Affichage du bouton permettant d'ouvrir la barre latéral sur la carte -->
            <button class="btn btn-secondary btn-lateral" id="btn-lateral" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
                <!-- Bandeau avec le titre et le bouton information -->
                <div class="offcanvas-header">
                    <a href="/"><img src="/assets/images/logo/param_safelane.png" alt="logo" class="header-image"></a>
                    <a id="infoButton"><img src="/assets/images/bouton_info.png" alt="info" class="bouton-info"></a>
                    <button type="button" class="btn-close" id="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div id="popup" class="popup">
                    <div class="popup-content">
                        <span class="close" onclick="closePopup()">X</span>
                        <h2>Fonctionnalités de <em>SAFELANE</em></h2>
                        <u>Voici les principales fonctionnalités de l'application :</u>
                        <ul>
                            <li>⚠️ Identification des zones à risque pour les cyclistes.</li>
                            <li>🚲 Consultation du Plan Vélo 2021-2026 de la mairie de Paris.</li>
                            <li>🗓️ Visualisation des données des accidents par année et par mois de chaque année.</li>
                            <li>⏯️ Lecture automatique de ses mêmes données toutes les secondes, avec option pause, arrêt et vitesses de lecture (x2 & xO.5).</li>
                            <li>🎥 Carte interactive 2D avec option 3D pour une visualisation plus détaillée du lieu de l'accident.</li>
                            <li>💬 Description de l'accident par ses caractéristiques visualisées dans une popup en cliquant dessus.</li>
                            <li>✅ Filtrage des accidents par caractéristiques (météo, infrastructure, luminosité, type d'intersection, ...).</li>
                            <li>🗺️ Personnalisation du fond de carte.</li>
                            <li>📈 Affichage de statistiques générales sur les accidents.</li>
                        </ul>
                    </div>
                </div>
                <!-- Contenu de la barre latéral -->
                <div class="offcanvas-body">
                    <div id="barre-laterale">
                        <!-- Affichage des 3 boutons caractéristiques : luminosité, météo et autre -->
                        <h3 id="titre-caract">Caractéristiques</h3>
                        <!-- Gestion de la luminosité -->
                        <div class="boutons-barre">
                            <div class="btn-group lumi">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="false">
                                    Luminosité
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

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

                                </div>
                            </div>
                        </div>
                        <!-- Gestion de la météo -->
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
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
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
                        <!-- Gestion des autres attributs -->
                        <div class="btn-group carac contenu-decalable">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Autres attributs
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
                        <div id="legendAcci"></div>

                        <!-- Bouton permettant de changer la couche des pistes -->
                        <h3 id="titre-pistes">Couches des voies cyclables</h3>
                        <button type="button" class="btn btn-primary" id="plan">Plan Vélo 2021-2026</button>
                        <!-- Affichage de la légende par défault -->
                        <div id="legend">
                            <h4>Légende</h4>
                            <div><span class="legend-color" style="background-color: #1D3FD9;"></span> Piste cyclable</div>
                            <div><span class="legend-color" style="background-color: #63DE6E;"></span> Voie verte / aménagement mixte</div>
                            <div><span class="legend-color" style="background-color: #EC1DD0;"></span> Couloir bus + vélo</div>
                            <div><span class="legend-color" style="background-color: #4DC0EF;"></span> Bande cyclable</div>
                            <div><span class="legend-color" style="background-color: #C1A4BD ;"></span> Voie mixte</div>
                        </div>

                        <!-- Affichage des statistiques -->
                        <h3 id="titre">Statistiques</h3>
                        <div class="btn-group">
                            <button id="stat" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Choisir
                            </button>
                            <!-- Menu déroulant des différentes statistiques calculées -->
                            <ul class="dropdown-menu position-fixed">
                                <li><a class="dropdown-item" onclick="showImageOverlay('assets/images/statistiques/categorie_velo.png')">Catégorie du vélo</a></li>
                                <li><a class="dropdown-item" onclick="showImageOverlay('assets/images/statistiques/type_intersection.png')">Type d'intersection</a></li>
                                <li><a class="dropdown-item" onclick="showImageOverlay('assets/images/statistiques/type_surface.png')">Type de surface</a></li>
                                <li><a class="dropdown-item" onclick="showImageOverlay('assets/images/statistiques/type_luminosite.png')">Type de luminosité</a></li>
                                <li><a class="dropdown-item" onclick="showImageOverlay('assets/images/statistiques/type_collision.png')">Type de collision</a></li>
                            </ul>
                        </div>

                        <!-- Affichage des différents fonds de carte disponibles -->
                        <h3 id="titre">Fonds de carte</h3>
                        <div class="button-container-fond">
                            <!-- Première ligne de boutons -->
                            <div class="button-row">
                                <button id="btnSatellite" class="map-button">
                                    <img src="assets/images/fond_carte/fond_aerien_paris.png" alt="Vue satellite">
                                    <span class="button-label-sat">Vue satellite</span>
                                </button>
                                <button id="btnTopographic" class="map-button">
                                    <img src="assets/images/fond_carte/fond_topo_paris.png" alt="Vue topographique">
                                    <span class="button-label">Vue topologique</span>
                                </button>
                            </div>
                            <!-- Deuxième ligne de boutons -->
                            <div class="button-row">
                                <button id="btnOpenStreetMap" class="map-button">
                                    <img src="assets/images/fond_carte/fond_routier_paris.png" alt="Vue OpenStreetMap">
                                    <span class="button-label">Vue OpenStreetMap</span>
                                </button>
                                <button id="btnDefault" class="map-button">
                                    <img src="assets/images/fond_carte/fond_gris_clair.png" alt="Vue routière">
                                    <span class="button-label">Vue par défaut</span>
                                </button>
                            </div>
                        </div>

                        <!-- Choix affichage des accidents ou non -->
                        <h3 id="titre">Affichage des accidents</h3>
                        <form>
                            <div id="acc" class="form-switch cluster mx-2">
                                <img id="img_acc" src="assets/images/accident.png" alt="Affichage accident">
                                Masquer les accidents
                                <input id="accidentsCheckbox" class="form-check-input mr-2" type="checkbox">
                            </div>
                        </form>
                    </div> <!--barre-laterale-->
                </div>
            </div>
        </div><!--carte-->
    </div><!--app-->

    <script src="/assets/safelane.js"></script>
    <script src="/assets/accueil.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!-- clef Gabin : AIzaSyCV613JJHOSp-JVbKMB7P8sxJlSt_wrK80 -->
<!-- clef Thomas : AIzaSyAuosDPx4wvSs6L__ZM1AtcJLjTaGq2P7w -->