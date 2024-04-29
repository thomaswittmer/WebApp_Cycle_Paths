<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>SAFELANE</title>
    <link rel="icon" type="image/png" href="/assets/images/icon_safelane_carre.png" sizes="32x32 64x64 128x128">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/vue@3.2.31"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="assets/map_style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.Default.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/leaflet.markercluster.js"></script>

    <script src="https://unpkg.com/esri-leaflet@3.0.10/dist/esri-leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.css" crossorigin=""/>
    <script src="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.js" crossorigin=""></script>
</head>
<body>
    <header>

    <!-- menu latéral à gauche en Bootstrap-->
    <nav class="navbar navbar-dark" style="background-color: #333;">
        <div class="container-fluid">
            <a class="navbar-brand"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" style="order: -1;">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
        
                <!--<div class = "menu-lateral">-->
                    <div class="offcanvas-header">
                        <h2 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Paramètres</h2>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <!-- Choix des paramètres -->
                <div class = "menu-lateral">

                        <!-- LUMINOSITE -->
                        <div class="boutons-barre">

                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Luminosité
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <form>
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
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Météo
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <form>
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
                            <div class="btn-group">
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
                            <button id="btnSatellite" class="map-button">
                                <img src="assets/images/fond_aerien_paris.png" alt="Vue satellite">
                                <span class="button-label-sat">Vue satellite</span>
                            </button>
                            <button id="btnTopographic" class="map-button">
                                <img src="assets/images/fond_topo_paris.png" alt="Vue topographique">
                                <span class="button-label">Vue topologique</span>
                            </button>
                            <button id="btnDefault" class="map-button">
                                <img src="assets/images/fond_routier_paris.png" alt="Vue routière">
                                <span class="button-label">Vue routière</span>
                            </button>
                        </div>
                </div>
            </div>
        </div>
    </nav>
        <img src="/assets/images/safelane_carre.png" alt="Logo" class="header-image">
        <h1>SAFELANE</h1>

        <div id="research_bar">
            <input class="form-control me-2" type="search" id="research_input" name="pacViewPlace" placeholder="Entrez un lieu..." aria-label="Search">
            <ul id="suggestions" class="dropdown-menu" style="display: none;"></ul>
        </div>

        <!--<div class="btn-stat">
            <button type="button" class="btn btn-warning">Statistiques</button>
            <button type="button" class="btn btn-warning dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" onclick="showImageOverlay('assets/images/categorie_velo.png')">Catégorie du vélo</a></li>
                <li><a class="dropdown-item" onclick="showImageOverlay('assets/images/type_intersection.png')">Type d'intersection</a></li>
                <li><a class="dropdown-item" onclick="showImageOverlay('assets/images/type_surface.png')">Type de surface</a></li>
                <li><a class="dropdown-item" onclick="showImageOverlay('assets/images/type_luminosite.png')">Type de luminosité</a></li>
                <li><a class="dropdown-item" onclick="showImageOverlay('assets/images/type_collision.png')">Type de collision</a></li>
            </ul>
        </div>-->

    </header>


    
    <div id=app>
        
        
        <div class="carte">
            <!--<div id="cesiumContainer"></div> -->
            <!-- curseur temporel -->
            <div id="map">
                <!-- affichage stats -->
                <div class="btn-stat">
                    <!--<button type="button" class="btn btn-statistique">Statistiques</button>-->
                    <button id="stat" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <!--<span class="visually-hidden">Toggle Dropdown</span>-->
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

                <!-- fermer les fenêtres des statistiques -->
                <div id="image-overlay">
                    <img id="overlayImage" src="" alt="Overlay Image">
                    <span class="close-button" onclick="closeImageOverlay()">X</span>
                </div>

                <!-- voir toutes les dates -->
                <div class="checkbox-date">
                    <input class="form-check-input mr-2" type="checkbox" value=1 v-model="caseChecked" id="checkboxdate" :disabled="caseDisabled" @change="annule_annee">
                    <span :class="{ 'anDesactive': caseDisabled }"> Toutes les années </span><br>
                </div>
                <!-- curseur temporel -->
                <div class="curseur-date">
                    <input type="range" min="2016" max="2022" v-model="selectedYear" id="dateSlider" @change="cherche_annee">
                    <p id="date"><strong>Date sélectionnée : {{ selectedYear }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    

    <script src="/assets/map.js"></script>
    <!-- <script src="/assets/leaflet.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>

<!-- clef Gabin : AIzaSyCV613JJHOSp-JVbKMB7P8sxJlSt_wrK80 -->
<!-- clef Thomas : AIzaSyAuosDPx4wvSs6L__ZM1AtcJLjTaGq2P7w -->