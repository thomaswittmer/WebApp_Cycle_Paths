<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte avec les tuiles Google 3D photoréalistiques</title>
    <link rel="icon" type="image/png" href="/assets/images/safelane.png" sizes="32x32 64x64">
    <script src="https://ajax.googleapis.com/ajax/libs/cesiumjs/1.105/Build/Cesium/Cesium.js"></script>
    <link href="https://ajax.googleapis.com/ajax/libs/cesiumjs/1.105/Build/Cesium/Widgets/widgets.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/map_style.css">
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    

    <div id="app">
        
        <!-- Barre latéral pour choisir les paramètres -->
        <div class="barre_laterale">
            <form id="Lumi" method='POST' action=''>
                Type de luminosité :<br>
                <input type="radio" name="lum" value="jour"> Jour<br>
                <input type="radio" name="lum" value="nuit_avec"> Nuit avec éclairage<br>
                <input type="radio" name="lum" value="nuit_sans"> Nuit sans éclairage<br>
            </form>
        </div>

        <!-- Contenu de la page d'accueil -->
        <div class="carte">
            <div id="cesiumContainer"></div>
            <!-- curseur temporel -->
            <div class="curseur-date">
                    <input type="range" min="2000" max="2022" v-model="selectedYear" id="dateSlider" @change="cherche_annee">
                    <p>Date sélectionnée : {{ selectedYear }}</p>
            </div>
        </div>
    </div>
    <script src="assets/map.js"></script>
    <script>
            // Enable simultaneous requests.
            Cesium.RequestScheduler.requestsByServer["tile.googleapis.com:443"] = 18;

            // Créer le viewer.
const viewer = new Cesium.Viewer('cesiumContainer', {
    imageryProvider: false,
    baseLayerPicker: false,
    geocoder: false,
    globe: false,
    // https://cesium.com/blog/2018/01/24/cesium-scene-rendering-performance/#enabling-request-render-mode
    requestRenderMode: true,
});

// Définir les coordonnées de Paris
const parisCoordinates = Cesium.Cartesian3.fromDegrees(2.3522, 48.8566);

// Déplacer la caméra vers Paris avec une altitude ajustée pour une vue d'ensemble de la ville
viewer.camera.flyTo({
    destination: Cesium.Cartesian3.fromDegrees(2.3522, 48.8566, 20000), // Ajouter l'altitude ici (50000 mètres)
    orientation: {
        heading: Cesium.Math.toRadians(0), // Orientation de la caméra en degrés
        pitch: Cesium.Math.toRadians(-90), // Inclinaison de la caméra en degrés
        roll: 0 // Rotation de la caméra en degrés
    },
});

// Ajouter le tileset 3D Tiles.
const tileset = viewer.scene.primitives.add(new Cesium.Cesium3DTileset({
    url: "https://tile.googleapis.com/v1/3dtiles/root.json?key=AIzaSyAuosDPx4wvSs6L__ZM1AtcJLjTaGq2P7w",
    // Cette propriété est nécessaire pour afficher correctement les attributions
    // si nécessaire.
    showCreditsOnScreen: true,
}));



    </script>
</body>
</html>
