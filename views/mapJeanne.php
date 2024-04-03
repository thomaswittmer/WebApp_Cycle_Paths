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
    

<div id="cesiumContainer" class="fullSize"></div>
    <div id="loadingOverlay"><h1>Loading...</h1></div>
    <div id="toolbar"></div>
    
    </div>
    <script src="assets/map.js"></script>
    <script>
           const viewer = new Cesium.Viewer("cesiumContainer", {
            timeline: false,
            animation: false,
            sceneModePicker: false,
            baseLayerPicker: false,
            // The globe does not need to be displayed,
            // since the Photorealistic 3D Tiles include terrain
            globe: false,
            });

            // Enable rendering the sky
            viewer.scene.skyAtmosphere.show = true;

            // Add Photorealistic 3D Tiles
            try {
            const tileset = await Cesium.createGooglePhotorealistic3DTileset();
            viewer.scene.primitives.add(tileset);
            } catch (error) {
            console.log(`Error loading Photorealistic 3D Tiles tileset.
            ${error}`);
            }

            // Point the camera at the Googleplex
            viewer.scene.camera.setView({
            destination: new Cesium.Cartesian3(
                -2693797.551060477,
                -4297135.517094725,
                3854700.7470414364
            ),
            orientation: new Cesium.HeadingPitchRoll(
                4.6550106925119925,
                -0.2863894863138836,
                1.3561760425773173e-7
            ),
            }); 
    </script>
    <script
        async=""
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuosDPx4wvSs6L__ZM1AtcJLjTaGq2P7w&libraries=places&callback=initAutocomplete"
    ></script>
</body>
</html>
