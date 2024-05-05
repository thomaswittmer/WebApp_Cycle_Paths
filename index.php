<?php
require 'flight/Flight.php';

// Database configuration
$server = 'localhost';
$port = '5432';
$base = 'amenagement_velo_paris';
$user = 'postgres';
$password = 'postgres';
$dsn = "host=$server port=$port dbname=$base user=$user password=$password";

// Connect to database
$link = pg_connect($dsn);

// Check connection and set Flight variable
if (!$link) {
    die('Erreur de connexion: ' . pg_last_error());
} else {
    Flight::set('BDD', $link);
}

Flight::route('/', function () {
    Flight::render('accueil');
});

Flight::route('POST /lumino', function () {

    // Récupérer les données POST envoyées par le client
    $lumi = isset($_POST['lumi']) ? $_POST['lumi'] : '';
    $meteo = isset($_POST['meteo']) ? $_POST['meteo'] : '';
    $link = Flight::get('BDD');

    $results = pg_query($link, "SELECT geom FROM accident_velo_2010_2022 WHERE lum = 1");

    $elements = [];
    while ($row = pg_fetch_assoc($results)) {
        $elements[] = $row['geom'];
    }

    Flight::json($elements);
});

Flight::route('POST /recup_annee', function () {
    $res = null;
    if (isset($_POST['annee'])) {
        $link = Flight::get('BDD');

        $accidents = pg_query($link, "SELECT *, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geo FROM voie_cyclable_geovelo WHERE annee <= '" . $_POST['annee'] . "' OR annee IS NULL;");

        $features = [];
        while ($row = pg_fetch_assoc($accidents)) {
            $geometry = json_decode($row['geo']);
            unset($row['geom']); // on retire la colonne geom pour ne garder que la geo en geojson
            $features[] = array(
                'type' => 'Feature',
                'geometry' => $geometry,
                'properties' => $row
            );
        }

        $geojson = array(
            'type' => 'FeatureCollection',
            'features' => $features
        );
    }
    Flight::json($geojson);
});

Flight::route('POST /recup_caractere', function () {
    if (isset($_POST['caractere'])) {
        $link = Flight::get('BDD');

        $caractere = pg_query($link, "SELECT DISTINCT (int) FROM accident_velo_2010_2022");

        $features = [];
        while ($row = pg_fetch_assoc($caractere)) {
            $features[] = $row;
        }

        $geojson = array(
            'type' => 'FeatureCollection',
            'features' => $features
        );
    }
    Flight::json($geojson);
});

Flight::route('/map3', function () {
    Flight::render('map3',);
});

Flight::route('/map4', function () {
    Flight::render('map4',);
});


Flight::route('GET /getAccidentCoordinates', function () {
    $link = Flight::get('BDD');

    // Vérifiez si num_acc est défini
    if (isset($_GET['num_acc']) && $_GET['num_acc'] !== null) {
        $num_acc = $_GET['num_acc'];

        // Utilisez une requête préparée pour éviter les injections SQL
        $stmt = pg_prepare($link, "get_accident", 'SELECT lat, long FROM accident_velo_2010_2022 WHERE num_acc = $1');

        // Exécutez la requête avec le paramètre num_acc
        $result = pg_execute($link, "get_accident", array($num_acc));

        // Récupérez les données
        $accident = pg_fetch_assoc($result);

        // Vérifiez si un accident a été trouvé
        if ($accident) {
            // Renvoyer les données en format JSON
            header('Content-Type: application/json');
            echo json_encode($accident);
        } else {
            // Renvoyer une erreur si aucun accident n'a été trouvé
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Accident non trouvé']);
        }
    } else {
        // Renvoyer une erreur si num_acc n'est pas défini
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Paramètre num_acc manquant']);
    }
});


Flight::route('/cesium', function () {
    Flight::render('cesium');
});

Flight::route('GET /recupere_pistes', function () {
    $link = Flight::get('BDD');

    $accidents = pg_query($link, "SELECT *, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geo FROM voie_cyclable_geovelo;");

    $features = [];
    while ($row = pg_fetch_assoc($accidents)) {
        $geometry = json_decode($row['geo']);
        unset($row['geom']); // on retire la colonne geom pour ne garder que la geo en geojson
        $features[] = array(
            'type' => 'Feature',
            'geometry' => $geometry,
            'properties' => $row
        );
    }

    $geojson = array(
        'type' => 'FeatureCollection',
        'features' => $features
    );

    Flight::json($geojson);
});


Flight::route('GET /recupere_acci', function () {
    $link = Flight::get('BDD');

    $accidents = pg_query($link, "SELECT *, ST_AsGeoJSON(geom) AS geo, EXTRACT(MONTH FROM TO_DATE(date, 'DD/MM/YYYY')) AS mois FROM accident_velo_2010_2022");

    $features = [];
    while ($row = pg_fetch_assoc($accidents)) {
        $geometry = json_decode($row['geo']);
        unset($row['geom']); // on retire la colonne geom pour ne garder que la geo en geojson
        $features[] = array(
            'type' => 'Feature',
            'geometry' => $geometry,
            'properties' => $row
        );
    }

    $geojson = array(
        'type' => 'FeatureCollection',
        'features' => $features
    );

    Flight::json($geojson);
});


Flight::route('GET /recupere_plan', function () {
    $link = Flight::get('BDD');

    $accidents = pg_query($link, "SELECT ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geom, statut FROM plan_velo;");

    $features = [];
    while ($row = pg_fetch_assoc($accidents)) {
        $geometry = json_decode($row['geom']);
        $features[] = array(
            'type' => 'Feature',
            'geometry' => $geometry,
            'properties' => array('statut' => $row['statut'])
        );
    }

    $geojson = array(
        'type' => 'FeatureCollection',
        'features' => $features
    );

    Flight::json($geojson);
});

Flight::start();
