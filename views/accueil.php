<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
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
  
    <div class="container">
        <h1>Connexion</h1>
        <form action="process_login.php" method="POST">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" value="Se connecter">
        </form>
    </div>


</body>
</html>
