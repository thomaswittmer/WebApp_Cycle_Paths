<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte avec les tuiles Google 3D photoréalistiques</title>
    <script src="https://ajax.googleapis.com/ajax/libs/cesiumjs/1.105/Build/Cesium/Cesium.js"></script>
    <link href="https://ajax.googleapis.com/ajax/libs/cesiumjs/1.105/Build/Cesium/Widgets/widgets.css" rel="stylesheet">
    <style>
        #cesiumContainer {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="cesiumContainer"></div>
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
</body>
</html>
