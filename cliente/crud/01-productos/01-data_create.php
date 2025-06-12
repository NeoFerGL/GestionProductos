<?php

function create_document($project, $collection, $document) {
    $url = 'https://'.$project.'.firebaseio.com/'.$collection.'.json';
    
    $ch =  curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH" );  // en sustitución de curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $document);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    curl_close($ch);

    // Se convierte a Object o NULL
    return json_decode($response);
}

$proyecto = 'productosws-35823-default-rtdb'; //Url
$coleccion = 'productos';

$data = '{
    "libros": {
        "LIB001": "El señor de los anillos",
        "LIB002": "Fundación",
        "LIB003": "The Pragmatic Programmer",
        "LIB004": "1984"
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

$data = '{
    "comics": {
        "COM001": "X-Men: Days of Future Past",
        "COM002": "Wolverine: Old Man Logan",
        "COM003": "X-Men: Dark Phoenix Saga",
        "COM004": "The Dark Knight Returns"
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

$data = '{
    "mangas": {
        "MAN001": "Naruto",
        "MAN002": "Mushoku Tensei",
        "MAN003": "One Piece",
        "MAN004": "Attack on Titan"
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

$data = '{
    "articulos": {
        "ART001": "Innovative Tech Trends",
        "ART002": "Health and Wellness Tips",
        "ART003": "Financial Freedom Guide",
        "ART004": "Travel Destinations 2024"
    }
}';


$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}
?>