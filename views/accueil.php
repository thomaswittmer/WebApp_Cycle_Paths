<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/accueil_style.css">
    <link rel="icon" type="image/png" href="/assets/images/safelane.png" sizes="32x32 64x64">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url("https://cdn.paris.fr/paris/2019/07/24/original-16a41c93d50a95cd15d8919d7101479f.jpg");
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 50%;
            height: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="/assets/images/safelane.png" height=200px>
        <p>Bienvenue sur ce site Internet favorisant la sécurité des cyclistes à Paris.</p>
    </div>
    <a href="connexion" class="login-button">Se connecter</a>
</body>
</html>
