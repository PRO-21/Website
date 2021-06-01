
<?php
include_once("jwtUtils.php");

if(isset($_POST['email']) && isset($_POST['password'])) {
    // Récupération des data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Création ressource cURL
    $url = 'https://pro.simeunovic.ch:8022/api/auth';
    $ch = curl_init($url);

    // Mise en forme des données en json
    $data = array(
        'auth_type' => 'credentials',
        'email' => "$email",
        'password' => "$password"
    );
    $payload = json_encode($data);

    // Paramètres
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if ($result['status']['code'] != 200) {
        //header("Location: index.php?loginError");
        echo -1;
    } else {
        // Application du cookie côté utilisateur.
        $token = $result['data']['token'];
        $tokenDecoded = decodeJWT($token); // Decodage du token JWT reçu de l'API
        setcookie(loginCookieName, $token, $tokenDecoded->exp, '/', 'pro.simeunovic.ch', true, true); // Deux derniers paramètres renforce la sécurité (voir la doc php de setcookie())
        //header('Location: index.php');
        echo 0;
    }
}


// *** Gestion du logout ***
if (isset($_GET['logout'])) {
    setcookie(loginCookieName, '', 1, '/', 'pro.simeunovic.ch', true, true);
    header('Location: /index.php');
}


?>


