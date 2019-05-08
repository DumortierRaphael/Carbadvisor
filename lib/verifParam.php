<?php
    // Création de la classe exception :
    class ParmsException extends Exception{};

    const CARBU_LIST = [
        '1',
        '2',
        '3',
        '4',
        '5',
        '6'
    ];

    const METHOD = INPUT_POST;
    $commune = filter_input(METHOD,'commune',FILTER_SANITIZE_STRING);
    if ($commune === NULL || $commune == " "){
        throw new ParmsException("Commune manquante.");
    }

    $range = filter_input(METHOD,'rayon',FILTER_SANITIZE_NUMBER_INT);
    if($range <= 0 || $range === NULL || $range === false){
        $range = 1;
    }


    $tab = filter_input(METHOD,'carburants',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
    if($tab === NULL || $tab === false){
        throw new ParmsException("Aucun carburants selectionné.");
    }
    
    $carburants = join(',', $tab);

?>