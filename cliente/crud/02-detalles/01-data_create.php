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

$proyecto = 'productosws-35823-default-rtdb';
$coleccion = 'detalles';

// LIBROS
$data = '{
    "LIB001": {
        "Autor": "J.R.R. Tolkien",
        "Nombre": "El señor de los anillos",
        "Editorial": "George Allen & Unwin",
        "Fecha": 1954,
        "Precio": 35.00,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}
$data = '{
    "LIB002": {
        "Autor": "Isaac Asimov",
        "Nombre": "Fundación",
        "Editorial": "Gnome Press",
        "Fecha": 1951,
        "Precio": 22.50,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

$data = '{
    "LIB003": {
        "Autor": "Andrew Hunt, David Thomas",
        "Nombre": "The Pragmatic Programmer",
        "Editorial": "Addison-Wesley",
        "Fecha": 1999,
        "Precio": 40.00,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

// Cuarto dato
$data = '{
    "LIB004": {
        "Autor": "George Orwell",
        "Nombre": "1984",
        "Editorial": "Secker & Warburg",
        "Fecha": 1949,
        "Precio": 19.99,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

// COMICS
$data = '{
    "COM001": {
        "Autor": "Chris Claremont, John Byrne",
        "Nombre": "X-Men: Days of Future Past",
        "Editorial": "Marvel Comics",
        "Fecha": 1981,
        "Precio": 20.99,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

$data = '{
    "COM002": {
        "Autor": "Mark Millar",
        "Nombre": "Wolverine: Old Man Logan",
        "Editorial": "Marvel Comics",
        "Fecha": 2008,
        "Precio": 18.50,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

$data = '{
    "COM003": {
        "Autor": "Chris Claremont, John Byrne",
        "Nombre": "X-Men: Dark Phoenix Saga",
        "Editorial": "Marvel Comics",
        "Fecha": 1980,
        "Precio": 25.00,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

$data = '{
    "COM004": {
        "Autor": "Frank Miller",
        "Nombre": "The Dark Knight Returns",
        "Editorial": "DC Comics",
        "Fecha": 1986,
        "Precio": 25.99,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

// MANGAS
$data = '{
    "MAN001": {
        "Autor": "Masashi Kishimoto",
        "Nombre": "Naruto",
        "Editorial": "Shueisha",
        "Fecha": 1999,
        "Precio": 10.00,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

$data = '{
    "MAN002": {
        "Autor": "Rifujin na Magonote",
        "Nombre": "Mushoku Tensei",
        "Editorial": "Media Factory",
        "Fecha": 2012,
        "Precio": 13.99,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

$data = '{
    "MAN003": {
        "Autor": "Eiichiro Oda",
        "Nombre": "One Piece",
        "Editorial": "Shueisha",
        "Fecha": 1997,
        "Precio": 11.99,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

$data = '{
    "MAN004": {
        "Autor": "Hajime Isayama",
        "Nombre": "Attack on Titan",
        "Editorial": "Kodansha",
        "Fecha": 2009,
        "Precio": 12.99,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

// Articulos
$data = '{
    "ART001": {
        "Autor": "John Doe",
        "Nombre": "Innovative Tech Trends",
        "Editorial": "Tech Today",
        "Fecha": 2023,
        "Precio": 5.99,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

$data = '{
    "ART002": {
        "Autor": "Jane Smith",
        "Nombre": "Health and Wellness Tips",
        "Editorial": "Healthy Living",
        "Fecha": 2023,
        "Precio": 6.99,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

$data = '{
    "ART003": {
        "Autor": "Albert Johnson",
        "Nombre": "Financial Freedom Guide",
        "Editorial": "Finance World",
        "Fecha": 2023,
        "Precio": 7.99,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

$data = '{
    "ART004": {
        "Autor": "Emily Davis",
        "Nombre": "Travel Destinations 2024",
        "Editorial": "Wanderlust",
        "Fecha": 2023,
        "Precio": 8.99,
        "Descuento": 0.0
    }
}';

$res = create_document($proyecto, $coleccion, $data);
if( !is_null($res) ) {
    echo '<br>Insersión exitosa<br>';
} else {
    echo '<br>Insersión fallida<br>';
}

?>