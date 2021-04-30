<?php
    function decodeJWT($jwt) {
        return json_decode(base64_decode(str_replace('', '/', str_replace('-','+',explode('.', $jwt)[1])))); // Decodage du token JWT reçu de l'API
    }

    define("loginCookieName", "loginSessionToken");
?>