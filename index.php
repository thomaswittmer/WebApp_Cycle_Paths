<?php

require 'flight/Flight.php';

Flight::route('/', function(){
    Flight::render('accueil');
});

Flight::route('/map', function(){
    Flight::render('map');
});

Flight::start();

?>