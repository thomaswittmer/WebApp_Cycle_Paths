<?php

require 'flight/Flight.php';

Flight::route('/', function(){
    Flight::render('accueil');
});

Flight::start();

?>