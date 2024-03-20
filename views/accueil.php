<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <link rel="stylesheet" href="assets/accueil_style.css">
    <title>Titre de la page d'accueil</title>
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
            <p>Ceci est une page d'accueil simple.</p>

            <!-- curseur temporel -->
            <div class="curseur-date">
                    <input type="range" min="2000" max="2022" v-model="selectedYear" id="dateSlider" @change="cherche_annee">
                    <p>Date sélectionnée : {{ selectedYear }}</p>
            </div>
        </div>
    </div>
    <script src="assets/accueil.js"></script>
</body>
</html>
