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