<?php

require 'flight/Flight.php';

Flight::route('/', function(){
    Flight::render('accueil');
});

Flight::route('POST /lumino', function(){
    $test = null;
    if (isset($_POST['lumi'])){
        $test = "luminosité : ".$_POST['lumi'];
    }
    Flight::json($test);
});

Flight::route('POST /recup_annee', function(){
    $test = null;
    if (isset($_POST['annee'])){
        $test = "année : ".$_POST['annee'];
    }
    Flight::json($test);
});

Flight::route('/map', function(){
    Flight::render('map');
});

Flight::route('/map2', function(){
    Flight::render('map2');
});

Flight::route('/cesium', function(){
    Flight::render('cesium');
});

Flight::start();

?>
