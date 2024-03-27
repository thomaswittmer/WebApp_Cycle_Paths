
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>CesiumJS 3D Tiles Simple Demo</title>
  <script src="https://ajax.googleapis.com/ajax/libs/cesiumjs/1.105/Build/Cesium/Cesium.js"></script>
  <link href="https://ajax.googleapis.com/ajax/libs/cesiumjs/1.105/Build/Cesium/Widgets/widgets.css" rel="stylesheet">
  <meta name="description" content="Une démo simple de CesiumJS utilisant des tuiles 3D.">
  <meta name="keywords" content="CesiumJS, tuiles 3D, démo">
  <meta name="author" content="Votre Nom / Votre Entreprise">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
    }
    #cesiumContainer {
      width: 100%;
      height: 100vh;
    }
    h1 {
      text-align: center;
      margin-top: 20px;
      color: #333;
    }
  </style>
</head>
<body>
  <h1>CesiumJS 3D Tiles Simple Demo</h1>
  <div id="cesiumContainer"></div>
  <script>
    // Enable simultaneous requests.
    Cesium.RequestScheduler.requestsByServer["tile.googleapis.com:443"] = 18;

    // Create the viewer.
    const viewer = new Cesium.Viewer('cesiumContainer', {
      imageryProvider: false,
      baseLayerPicker: false,
      geocoder: false,
      globe: false,
      // https://cesium.com/blog/2018/01/24/cesium-scene-rendering-performance/#enabling-request-render-mode
      requestRenderMode: true,
    });

    // Add 3D Tiles tileset.
    const tileset = viewer.scene.primitives.add(new Cesium.Cesium3DTileset({
      url: "https://tile.googleapis.com/v1/3dtiles/root.json?key=AIzaSyAuosDPx4wvSs6L__ZM1AtcJLjTaGq2P7w",
      // This property is needed to appropriately display attributions
      // as required.
      showCreditsOnScreen: true,
    }));
  </script>
</body>
</html>
