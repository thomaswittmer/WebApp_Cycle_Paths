<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/accueil_style.css">
    <link rel="icon" type="image/png" href="/assets/images/icon_safelane_carre.png" sizes="32x32">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>
    <div class="container">
        <img src="/assets/images/safelane_carre.png" height=200px><br>
        <h1>Bienvenue sur l'application SAFELANE</h1><br>

        <p>Une application web a été développée pour répondre à l'augmentation des accidents de vélo à Paris entre 2016 et 2022 
        malgré les aménagements cyclables existants. Cette application vise à identifier les zones à risque les plus élevés pour les 
        cyclistes, afin de prioriser les améliorations nécessaires. Elle offrira une carte interactive 2D avec une option 3D pour une 
        visualisation détaillée des données, permettant une meilleure planification urbaine. En plus d'améliorer la sécurité des cyclistes 
        parisiens, cette application pourrait inspirer d'autres grandes villes à adopter des solutions similaires, contribuant ainsi à une 
        évolution positive de la mobilité cycliste à l'échelle mondiale.</p>

        <!-- Groupe de boutons pour ouvrir la popup et accéder à la carte -->
        <div class="button-group">
            <button class="btn btn-outline-danger btn-lg mt-3" onclick="openPopup()">Voir les fonctionnalités</button>
            <a href="/map3" class="btn btn-success btn-lg mt-3">Accéder à l'application</a>
        </div>

        <div id="popup" class="popup">
            <div class="popup-content">
                <span class="close" onclick="closePopup()">X</span>
                <h2>Fonctionnalités de SAFELANE</h2>
                <p>Voici les principales fonctionnalités de l'application :</p>
                <ul>
                    <li>⚠️ Identification des zones à risque pour les cyclistes.</li>
                    <li>🚲 Consultation du Plan Vélo 2021-2026 de la mairie de Paris.</li>
                    <li>🗓️ Visualisation des données des accidents par année.</li>
                    <li>🎥 Carte interactive 2D avec option 3D pour une visualisation plus détaillée du lieu de l'accident.</li>
                    <li>✅ Filtrage des accidents par caractéristiques (météo, infrastructure, luminosité, ...).</li>
                    <li>🗺️ Personnalisation du fond de carte.</li>
                    <li>📈 Affichage de statistiques sur les accidents.</li>
                </ul>
            </div>
        </div>
    </div>
    <script src="/assets/accueil.js"></script>
</body>
</html>
