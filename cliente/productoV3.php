<?php
    // Derechos reservados Fernando Garza De La Luz
    ini_set("log_errors", 1);
    ini_set("error_log", "reportes/php-error-producto.log");

    require_once 'vendor/autoload.php';
    require_once 'MyFirebase.php'; // Incluir la clase MyFirebase

    $firebase = new MyFirebase(''); // Instancia de la clase poner credenciales

    $server = new soap_server();
    $server->configureWSDL('WebServicesBUAP', 'urn:buap_api');
    $server->soap_defencoding = 'UTF-8';
    $server->decode_utf8 = false;
    $server->encode_utf8 = true;

    $server->wsdl->addComplexType(
        'RespuestaGetProd',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'code'          => ['name' => 'code',          'type' => 'xsd:string'],
            'message'       => ['name' => 'message',       'type' => 'xsd:string'],
            'data'          => ['name' => 'data',          'type' => 'xsd:string'],
            'status'        => ['name' => 'status',        'type' => 'xsd:string']
        )
    );

    $server->wsdl->addComplexType(
        'RespuestaGetDetails',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'code'          => ['name' => 'code',          'type' => 'xsd:string'],
            'message'       => ['name' => 'message',       'type' => 'xsd:string'],
            'data'          => ['name' => 'data',          'type' => 'xsd:string'],
            'status'        => ['name' => 'status',        'type' => 'xsd:string'],
            'oferta'        => ['name' => 'oferta',        'type' => 'xsd:boolean']
        )
    );

    $server->register( 
        'getProd',                               // Nombre de la operación (método)
        array('user' => 'xsd:string', 'pass' => 'xsd:string', 'categoria' => 'xsd:string'),     // Lista de parámetros
        array('return' => 'tns:RespuestaGetProd'), // Respuesta; de tipo simple o de tipo complejo
        'urn:producto',                         // Namespace para entradas (input) y salidas (output)
        'urn:producto#getProd',                 // Nombre de la acción (soapAction)
        'rpc',                                  // Estilo de comunicación (rpc|document)
        'encoded',                              // Tipo de uso (encoded|literal)
        'Nos da una lista de productos de cada categoría.'  // Documentación o descripción del método
    );

    $server->register( 
        'getDetails',                            // Nombre de la operación (método)
        array('user' => 'xsd:string', 'pass' => 'xsd:string', 'isbn' => 'xsd:string'),     // Lista de parámetros
        array('return' => 'tns:RespuestaGetDetails'), // Respuesta; de tipo simple o de tipo complejo
        'urn:producto',                         // Namespace para entradas (input) y salidas (output)
        'urn:producto#getDetails',              // Nombre de la acción (soapAction)
        'rpc',                                  // Estilo de comunicación (rpc|document)
        'encoded',                              // Tipo de uso (encoded|literal)
        'Nos da una lista de detalles de cada producto.'  // Documentación o descripción del método
    );

    // Funcion para libros, articulos, mangas, comics
    function getProd($user, $pass, $categoria) {
        global $firebase; // Aquí uso la instancia de MyFirebase

        $resp = array(
            'code'    => 999,
            'message' => 'Error no identificado',
            'data'    => '',
            'status'  => 'error'
        );

        // Validar el usuario y la contraseña
        $hashedPass = md5($pass);
        $storedPass = $firebase->obtainPassword($user);

        if ($firebase->isUserInDB($user)) {
            if ($storedPass === $hashedPass) {
                $products = $firebase->obtainProducts($categoria);
                if ($products) {
                    $resp['code'] = 200;
                    $resp['message'] = 'Categoría encontrada exitosamente.';
                    $resp['status'] = 'success';
                    // Serializar el array de detalles a JSON formateado para devolverlo como string
                    $resp['data'] = json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); // JSON_UNESCAPED_UNICODE evita el escape de caracteres
                } else {
                    $resp['code'] = 300;
                    $resp['message'] = 'Categoría no encontrada.';
                }
            } else {
                $resp['code'] = 501;
                $resp['message'] = 'Password no reconocido.';
            }
        } else {
            $resp['code'] = 500;
            $resp['message'] = 'Usuario no reconocido.';
        }

        return $resp;
    }

    function getDetails($user, $pass, $isbn) {
        global $firebase; // Usar la instancia de MyFirebase

        $resp = array(
            'code'    => 999,
            'message' => 'Error no identificado',
            'data'    => '',
            'status'  => 'error',
            'oferta'  => false
        );

        $hashedPass = md5($pass);
        $storedPass = $firebase->obtainPassword($user);

        if ($firebase->isUserInDB($user)) {
            if ($storedPass === $hashedPass) {
                $details = $firebase->obtainDetails($isbn);
                if ($details) {
                    $resp['code'] = 201;
                    $resp['message'] = 'ISBN encontrado exitosamente.';
                    $resp['status'] = 'success';
                    // Serializar el array de detalles a JSON formateado para devolverlo como string
                    $resp['data'] = json_encode($details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); // JSON_UNESCAPED_UNICODE evita el escape de caracteres
                    $resp['oferta'] = $details['Descuento'] > 0;
                } else {
                    $resp['code'] = 301;
                    $resp['message'] = 'ISBN no encontrado.';
                }
            } else {
                $resp['code'] = 501;
                $resp['message'] = 'Password no reconocido.';
            }
        } else {
            $resp['code'] = 500;
            $resp['message'] = 'Usuario no reconocido.';
        }

        return $resp;
    }

    // Exposición del servicio (WSDL) PHP v7.2 o superior
    @$server->service(file_get_contents("php://input"));
?>
