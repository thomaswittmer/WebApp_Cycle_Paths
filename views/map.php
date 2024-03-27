<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte avec les tuiles Google 3D photoréalistiques</title>
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
        <div id="cesiumContainer">
            <script>
                const viewer = new Cesium.Viewer('cesiumContainer', {
                    imageryProvider: false,
                    baseLayerPicker: false,
                    requestRenderMode: false,
                });

                const tileset = viewer.scene.primitives.add(new Cesium.Cesium3DTileset({
                    url: "https://tile.googleapis.com/v1/3dtiles/root.json?key=AIzaSyCIxj-Cm4icWXMl_lPgircWNPg-Hhpc8Nw",
                    showCreditsOnScreen: false,
                }));

                viewer.scene.globe.show = true;
            </script>

            <!-- curseur temporel -->
            <div class="curseur-date">
                    <input type="range" min="2000" max="2022" v-model="selectedYear" id="dateSlider" @change="cherche_annee">
                    <p>Date sélectionnée : {{ selectedYear }}</p>
            </div>
        </div>
    </div>
    <script src="assets/map.js"></script>
</body>
</html>
