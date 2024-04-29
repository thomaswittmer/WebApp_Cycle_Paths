<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>SAFELANE 3D</title>
    <link rel="icon" type="image/png" href="/assets/images/safelane.png" sizes="32x32 64x64">
    <script src="https://ajax.googleapis.com/ajax/libs/cesiumjs/1.105/Build/Cesium/Cesium.js"></script>
    <link href="https://ajax.googleapis.com/ajax/libs/cesiumjs/1.105/Build/Cesium/Widgets/widgets.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="assets/map_style.css">
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

        .barre_laterale{
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

        /* Style pour le header */
        header {
            background-color: #333; /* Couleur de fond */
            color: #fff; /* Couleur du texte */
            padding: 10px; /* Espacement intérieur */
            display: flex; /* Utilisation de flexbox */
            align-items: center; /* Centrer verticalement les éléments */
            justify-content: space-between;
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

        
        .return-button {
            background-color: red; /* Couleur de fond */
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

    </style>
</head>
<body>
    <header>
        <img src="/assets/images/safelane.png" alt="Logo" class="header-image"> 
        <h1>SAFELANE</h1>
        <a href="map3" class="return-button">Retour</a> 
    </header>

    
    <div id=app>
            <div id="cesiumContainer"></div>
    </div>


    <script>

    // Enable simultaneous requests.
    Cesium.RequestScheduler.requestsByServer["tile.googleapis.com:443"] = 18;
    // Fonction pour récupérer les coordonnées de l'accident depuis la base de données
    function getAccidentCoordinatesFromDB(num_acc) {
        return new Promise((resolve, reject) => {
            // URL de votre API pour récupérer les coordonnées d'un accident
            const apiUrl = `http://localhost:8000/getAccidentCoordinates?num_acc=${num_acc}`;

            // Effectuer la requête AJAX
            $.ajax({
                url: apiUrl,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Vérifier si les coordonnées ont été récupérées avec succès
                    if (response && response.lat && response.long) {
                        const coordinates = {
                            latitude: response.lat,
                            longitude: response.long
                        };
                        resolve(coordinates);
                    } else {
                        reject('Erreur lors de la récupération des coordonnées');
                    }
                },
                error: function(error) {
                    reject('Erreur lors de la requête à la base de données');
                }
            });
        });
    }

    // Récupération du paramètre accidentId depuis l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const accidentId = urlParams.get('accidentId');

    // Appel de la fonction pour récupérer les coordonnées de l'accident
    getAccidentCoordinatesFromDB(accidentId)
        .then(coordinates => {
            // Initialisation de la vue Cesium avec les coordonnées récupérées
            const viewer = new Cesium.Viewer('cesiumContainer', {
                baseLayerPicker: false,
                geocoder: false,
                homeButton: false,
                navigationHelpButton: false,
                sceneModePicker: false,
                fullscreenButton: false,
                animation: false,
                timeline: false,
            });
            viewer.scene.skyAtmosphere.show = true;



           // Déplacer la caméra vers le point avec une altitude ajustée
            viewer.camera.flyTo({
                destination: Cesium.Cartesian3.fromDegrees(coordinates.longitude, coordinates.latitude - 0.0015, 300),
                orientation: {
                    heading: Cesium.Math.toRadians(0), // Orientation de la caméra en degrés
                    pitch: Cesium.Math.toRadians(-50), // Inclinaison de la caméra en degrés
                    roll: 0 // Rotation de la caméra en degrés
                },
            });




            // Chargement du tileset
            const tileset = new Cesium.Cesium3DTileset({
                url: 'https://tile.googleapis.com/v1/3dtiles/root.json?key=AIzaSyCV613JJHOSp-JVbKMB7P8sxJlSt_wrK80'
            });
            viewer.scene.primitives.add(tileset);
            viewer.zoomTo(tileset);




            function getAltitudeFromCoordinates(lat, long) {
                const apiKey = 'AIzaSyCV613JJHOSp-JVbKMB7P8sxJlSt_wrK80'; // Remplacez YOUR_API_KEY par votre propre clé d'API

                const apiUrl = `https://api.open-elevation.com/api/v1/lookup?locations=${lat},${long}&key=${apiKey}`;

                return new Promise((resolve, reject) => {
                    fetch(apiUrl)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.results && data.results.length > 0) {
                                const altitude = data.results[0].elevation;
                                resolve(altitude);
                            } else {
                                reject('Impossible de récupérer l\'altitude');
                            }
                        })
                        .catch(error => {
                            reject('Erreur lors de la requête pour récupérer l\'altitude');
                        });
                });
            }
            




            getAccidentCoordinatesFromDB(accidentId)
                .then(coordinates => {
                    // Utilisation de la fonction pour obtenir l'altitude à partir des coordonnées de l'accident
                    getAltitudeFromCoordinates(coordinates.latitude, coordinates.longitude)
                        .then(altitude => {
                            console.log('Altitude:', altitude);
                            
                            // Création d'un point sur la carte avec les coordonnées et l'altitude récupérées
                            const point = viewer.entities.add({
                                name: 'Accident',
                                position: Cesium.Cartesian3.fromDegrees(
                                    coordinates.longitude,
                                    coordinates.latitude,
                                    altitude  // Utilisation de l'altitude récupérée ici
                                ),
                                point: {
                                    pixelSize: 10,
                                    color: Cesium.Color.RED,
                                    outlineColor: Cesium.Color.WHITE,
                                    outlineWidth: 2,
                                },
                            });

                            // Déplacer la caméra vers le point avec une altitude ajustée
                            viewer.camera.flyTo({
                                destination: Cesium.Cartesian3.fromDegrees(coordinates.longitude, coordinates.latitude, altitude + 300),
                                orientation: {
                                    heading: Cesium.Math.toRadians(0), // Orientation de la caméra en degrés
                                    pitch: Cesium.Math.toRadians(-50), // Inclinaison de la caméra en degrés
                                    roll: 0 // Rotation de la caméra en degrés
                                },
                            });
                        })
                        .catch(error => {
                            console.error('Erreur lors de la récupération de l\'altitude:', error);
                        });
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des coordonnées de l\'accident:', error);
                });


        // Define the zoomToViewport function
        function zoomToViewport(viewport) {
            viewer.camera.flyTo({
                destination: Cesium.Rectangle.fromDegrees(
                    viewport.getSouthWest().lng(), 
                    viewport.getSouthWest().lat(), 
                    viewport.getNorthEast().lng(), 
                    viewport.getNorthEast().lat()
                ),
            });
        }
        });
    </script>

        


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!-- clef Gabin : AIzaSyCV613JJHOSp-JVbKMB7P8sxJlSt_wrK80 -->
<!-- clef Thomas : AIzaSyAuosDPx4wvSs6L__ZM1AtcJLjTaGq2P7w -->