<?php
// Derechos reservados Fernando Garza De La Luz
class MyFirebase {
    private $project;
    private $baseUrl;

    public function __construct($project) {
        $this->project = $project;
        $this->baseUrl = 'https://' . $this->project . '.firebaseio.com/';
    }

    private function runCurl($url, $method = 'GET', $data = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($method == 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    // Método para construir la URL completa basada en una ruta
    private function buildUrl($path) {
        return $this->baseUrl . $path . '.json';
    }

    // Método buildUrl(): Este método toma una ruta como parámetro (usuarios/<name>, productos/<category>, etc.) y construye la URL completa añadiendo .json automáticamente.
    public function isUserInDB($name) {
        $url = $this->buildUrl('usuarios/' . $name);
        $response = file_get_contents($url);
        return $response !== 'null';
    }

    public function obtainPassword($user) {
        $url = $this->buildUrl('usuarios/' . $user);
        $response = file_get_contents($url);
        $response = trim($response, '"'); // Elimina las comillas si existen
        return $response !== 'null' ? $response : null;
    }

    public function isCategoryInDB($name) {
        $url = $this->buildUrl('productos/' . $name);
        $response = file_get_contents($url);
        return $response !== 'null';
    }

    public function obtainProducts($category) {
        $url = $this->buildUrl('productos/' . $category);
        $response = file_get_contents($url);
        return json_decode($response, true);
    }
    // 'https://' . $this->project . '.firebaseio.com/detalles.json?orderBy="isbn"&equalTo="' . $clave . '"';
    // https://productosws-35823-default-rtdb.firebaseio.com/detalles/ART001.json
    public function isIsbnInDB($clave) {
        $url = $this->buildUrl('detalles/' . $clave);
        $response = file_get_contents($url);
        $details = json_decode($response, true);
        return !empty($details);
    }

    public function obtainDetails($isbn) {
        $url = $this->buildUrl('detalles/' . $isbn);
        $response = file_get_contents($url);
        $details = json_decode($response, true);
        return $details ? $details : null;
    }

    public function obtainMessage($code) {
        $url = $this->buildUrl('respuestas/' . $code);
        $response = file_get_contents($url);
        $message = json_decode($response, true);
        return $message;
    }
}
?>
