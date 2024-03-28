<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>CesiumJS 3D Tiles Places API Integration Demo</title>
    <script src="https://ajax.googleapis.com/ajax/libs/cesiumjs/1.105/Build/Cesium/Cesium.js"></script>
    <link href="https://ajax.googleapis.com/ajax/libs/cesiumjs/1.105/Build/Cesium/Widgets/widgets.css" rel="stylesheet">
    <style>
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
            margin-left: auto; /* Permet de pousser la barre de recherche et le bouton à droite */
            display: flex; /* Utilisation de flexbox */
            align-items: center; /* Centrer verticalement les éléments */
        }

        #research_bar input[type="text"] {
            padding: 8px; /* Espacement intérieur */
            border: none; /* Pas de bordure */
            border-radius: 5px; /* Coins arrondis */
            margin-right: 5px; /* Espacement à droite */
            font-size: 16px; /* Taille de la police */
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

    </style>
</head>
<body>
    <header>
        <img src="/assets/images/safelane.png" alt="Logo" class="header-image"> <!-- Assurez-vous de remplacer "votre-image.jpg" par le chemin de votre image -->
        <h1>SAFELANE</h1>
        <div id="research_bar">
            <input
                type="text"
                id="pacViewPlace"
                name="pacViewPlace"
                placeholder="Entrez un lieu..."
            />
        </div>
        <a href="connexion" class="connexion-button">Connexion</a> <!-- Lien vers connexion.php -->
    </header>


    

    <div id="cesiumContainer"></div>
    <script>
    // Enable simultaneous requests.
    Cesium.RequestScheduler.requestsByServer["tile.googleapis.com:443"] = 18;

    // Create the viewer.
    const viewer = new Cesium.Viewer("cesiumContainer", {
        imageryProvider: false,
        baseLayerPicker: false,
        requestRenderMode: true,
        geocoder: false,
        globe: false,
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

    // Add 3D Tiles tileset.
    const tileset = viewer.scene.primitives.add(
        new Cesium.Cesium3DTileset({
        url: "https://tile.googleapis.com/v1/3dtiles/root.json?key=AIzaSyAuosDPx4wvSs6L__ZM1AtcJLjTaGq2P7w",
        // This property is required to display attributions as required.
        showCreditsOnScreen: true,
        })
    );

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

    function initAutocomplete() {
        const autocomplete = new google.maps.places.Autocomplete(
        document.getElementById("pacViewPlace"),
        {
            fields: [
            "geometry",
            "name",
            ],
        }
        );
        autocomplete.addListener("place_changed", () => {
        const place = autocomplete.getPlace();
        if (!place.geometry || !place.geometry.viewport) {
            window.alert("No viewport for input: " + place.name);
            return;
        }
        zoomToViewport(place.geometry.viewport);
        });
    }
    </script>
    <script
    async=""
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuosDPx4wvSs6L__ZM1AtcJLjTaGq2P7w&libraries=places&callback=initAutocomplete"
    ></script>
</body>
</html>
