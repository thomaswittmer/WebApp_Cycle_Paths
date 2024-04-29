<?php
//////FICHIER NON UTILE












// Configuration de la base de données
$host = 'localhost';
$dbname = 'amenagement_velo_paris';
$username = 'postgres';
$password = "getenv('DB_PASSWORD')"; // Retrieve password from environment variable

try {
    // Connexion à la base de données
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);

    // Récupérer le num_acc depuis la requête
    $num_acc = $_GET['num_acc'];

    // Préparer la requête SQL
    $stmt = $pdo->prepare('SELECT lat, long FROM accident_velo_2010_2022 WHERE num_acc = :num_acc');

    // Exécuter la requête
    $stmt->execute(['num_acc' => $num_acc]);

    // Récupérer les données
    $accident = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si un accident a été trouvé
    if ($accident) {
        // Renvoyer les données en format JSON
        echo json_encode($accident);
    } else {
        // Renvoyer une erreur si aucun accident n'a été trouvé
        echo json_encode(['error' => 'Accident non trouvé']);
    }
} catch (PDOException $e) {
    // Renvoyer une erreur si la connexion à la base de données échoue
    $errorMessage = 'Erreur de connexion à la base de données: ' . $e->getMessage();
    error_log($errorMessage); // Log the error
    echo json_encode(['error' => $errorMessage]);
}
?>
