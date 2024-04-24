<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>SAFELANE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/vue@3.2.31"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="assets/map_style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.Default.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/leaflet.markercluster.js"></script>

    <script src="https://unpkg.com/esri-leaflet@3.0.10/dist/esri-leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.css" crossorigin="" />
    <script src="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.js" crossorigin=""></script>

    <style>
        body{
            background-color: #333;
            height: 50vh;
            margin: 0;
            padding: 0;
            max-height: 100vh;
            /*overflow: hidden;*/
        }

        #app{
            display: flex;
            flex-direction: row;
            align-items: start;
            background-color: #333;
            padding: 30px;
        }

        .barre-laterale{
            display: flex;
            flex-direction: column;
            align-items : center;
            justify-content: center;
            background-color: #333;
            grid-column: 1;
            align-self: start;
            height: 73.5vh;
            width: 260px;
            color: white;
            padding : 20px;
        }
        .legend{
            top: 10px;
            right: 10px;
            background-color: white;
            border: 1px solid #ccc;
            padding: 10px;
            z-index: 1000;
        }

        .legend-color {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
            vertical-align: middle;
        }

        /* Style pour le header */
        header {
            background-color: #333; /* Couleur de fond */
            color: #fff; /* Couleur du texte */
            padding: 10px; /* Espacement intérieur */
            display: flex; /* Utilisation de flexbox */
            align-items: center; /* Centrer verticalement les éléments */
            justify-content: center;
        }

        /* Style pour le titre */
        h1 {
            margin: 0; /* Réinitialiser les marges */
            margin-left: 5px; /* Nouvelle marge à gauche */
            font-family: 'Zen Dots';font-size: 32px;
        }

        .header-image {
            width: 50px; /* Largeur de l'image */
            height: auto; /* Hauteur automatique en maintenant le ratio */
            margin-right: 10px; /* Espacement à droite */
        }

        #research_bar {
            margin-left: auto;
            align-items: center; /* Centrer verticalement les éléments */
        }

        #research_input[type="text"] {
            align-items: center;
            padding: 8px; /* Espacement intérieur */
            border: none; /* Pas de bordure */
            border-radius: 5px; /* Coins arrondis */
            margin-right: 5px; /* Espacement à droite */
            font-size: 16px; /* Taille de la police */
        }

        .dropdown-item {
        cursor: pointer;
        }
        .connexion-button {
            background-color: #007bff; /* Couleur de fond */
            color: #fff; /* Couleur du texte */
            padding: 10px 20px; /* Espacement intérieur */
            border: none; /* Pas de bordure */
            border-radius: 5px; /* Coins arrondis */
            font-family: 'Zen Dots';
            cursor: pointer; /* Curseur au survol */
            text-decoration: none; /* Suppression du soulignement */
            margin-left: 10px; /* Espacement à gauche */
        }
        
        .carte {
            grid-column: 2;
            display: grid;
            grid-template-rows: 650px 1fr;
            height: 100%;
            width: 100%;
        }        

        .curseur-date {
            grid-row: 2;
            width: 100%;
            text-align: center;
            margin-top: 20px;
            color: white;
            display: flex;
            flex-direction: column; /* Nouveau style en colonne */
            align-items: flex-start; /* Alignement à gauche */
        }

        .checkbox-date {
            margin-bottom: 10px; /* Espacement entre la case à cocher et le slider */
        }

        #dateSlider {
            width: 100%; 
            margin-bottom: 10px;
        }

        #date{
            margin:auto;
        }

        input[type="range"] {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 10px;
            background: #d3d3d3; /* Couleur de fond du curseur */
        }

        input[type="range"]::-webkit-slider-thumb,
        input[type="range"]::-moz-range-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 50px; /* Largeur de l'image */
            height: 50px; /* Hauteur de l'image */
            background: url('../assets/images/safelane.png') center center no-repeat; /* Chemin vers l'image */
            background-size: contain;
            cursor: pointer;
            border: none;
        }

    </style>
</head>
<body>
    <header>
        <img src="/assets/images/safelane.png" alt="Logo" class="header-image"> <!-- Assurez-vous de remplacer "votre-image.jpg" par le chemin de votre image -->
        <h1>SAFELANE</h1>
        <div id="research_bar">
            <input
                type="search"
                id="research_input"
                name="pacViewPlace"
                placeholder="Entrez un lieu..."
            />
            <ul id="suggestions" class="dropdown-menu" style="display: none;">
        
        </ul>
        </div>

        
    </header>


    
    <div id=app>
            <!-- Barre latérale pour choisir les paramètres -->
        <div class="barre-laterale">
            <!-- LUMINOSITE -->
            <div class="boutons-barre">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Luminosité
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <form>
                        <div class="dropdown-item lumi">
                            <input class="form-check-input mr-2" type="checkbox" value="Plein jour" checked> Plein jour<br>
                        </div>
                        <div class="dropdown-item lumi">
                            <input class="form-check-input mr-2" type="checkbox" value="Crépuscule ou aube" checked> Crépuscule ou aube<br>
                        </div>
                        <div class="dropdown-item lumi">
                            <input class="form-check-input mr-2" type="checkbox" value="Nuit sans éclairage public" checked> Nuit sans éclairage public<br>
                        </div>
                        <div class="dropdown-item lumi">
                            <input class="form-check-input mr-2" type="checkbox" value="Nuit avec éclairage public non allumé" checked> Nuit avec éclairage public non allumé<br>
                        </div>
                        <div class="dropdown-item lumi">
                            <input class="form-check-input mr-2" type="checkbox" value="Nuit avec éclairage public allumé" checked> Nuit avec éclairage public allumé<br>
                        </div>
                    </form>
                </div>

                <!-- CARACTERISTIQUES -->
                <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Caractéristiques
                </button>
                    <div class="dropdown-menu">
                        <form>
                            <a class="dropdown-item" value="int">Type d'intersection</a>
                            <a class="dropdown-item" value="col">Type de collision</a>
                            <a class="dropdown-item" value="atm">Conditions atmosphériques</a>
                            <a class="dropdown-item" value="catr">Catégorie de route</a>
                            <a class="dropdown-item" value="surf">Etat de la route</a>
                            <a class="dropdown-item" value="infra">Infrastructure de la route</a>
                            <a class="dropdown-item" value="catv">Catégorie du véhicule</a>
                        </form>
                    </div>
                </div>

                <button type="button" class="btn btn-primary" id="plan">Plan Vélo 2024</button>

                <div id="legend">
                <h3>Légende</h3>
                <div><span class="legend-color" style="background-color: #1D3FD9;"></span> piste cyclable</div>
                <div><span class="legend-color" style="background-color: #63DE6E;"></span> voie verte / aménagement mixte</div>
                <div><span class="legend-color" style="background-color: #EC1DD0;"></span> couloir bus + vélo</div>
                <div><span class="legend-color" style="background-color: #4DC0EF;"></span> bande cyclable</div>
                <div><span class="legend-color" style="background-color: #C1A4BD ;"></span> voie mixte</div>
                
    </div>
            </div>
            

        </div>
        <div class="carte">
            <!--<div id="cesiumContainer"></div> -->
            <!-- curseur temporel -->
            <div id="map"></div>
            <div class="curseur-date">
                <div class="checkbox-date">
                    <input class="form-check-input mr-2" type="checkbox" value=1 v-model="caseChecked" id="checkboxdate" :disabled="caseDisabled" @change="annule_annee">
                    <span :class="{ 'anDesactive': caseDisabled }"> Toutes les années </span><br>
                </div>
                    <input type="range" min="2016" max="2022" v-model="selectedYear" id="dateSlider" @change="cherche_annee">
                    <!--<input class="form-check-input mr-2" type="checkbox" value=1 v-model="caseChecked"  :disabled="caseDisabled" @change="annule_annee"> <span :class="{ 'anDesactive': caseDisabled }"> Toutes les années </span><br>-->
                    <p id="date">Date sélectionnée : {{ selectedYear }}</p>
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