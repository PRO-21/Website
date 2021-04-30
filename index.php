<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="The homepage of the website">
  <meta name="author" content="Simeunovic, Viotti">

  <title>Homepage</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template -->
  <link href="css/landing-page.min.css" rel="stylesheet">

</head>

<body>

<?php
include("struct/header.php");
include_once ("utils/jwtUtils.php");



// *** Gestion de la connexion d'un utilisateur ***
if (isset($_POST['signIn'])) {
    // Récupération des data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Création ressource cURL
    $url = 'https://pro.simeunovic.ch:8022/protest/api/auth';
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
        header("Location: index.php?loginError");
    } else {
        // Application du cookie côté utilisateur.
        $token = $result['data']['token'];
        $tokenDecoded = decodeJWT($token); // Decodage du token JWT reçu de l'API
        setcookie(loginCookieName, $token, $tokenDecoded->exp, '', 'pro.simeunovic.ch', true, true); // Deux derniers paramètres renforce la sécurité (voir la doc php de setcookie())
        header('Location: index.php');
    }

}

// *** Gestion du logout ***
if (isset($_GET['logout'])) {
    setcookie(loginCookieName, '', 1, '', 'pro.simeunovic.ch', true, true);
    header('Location: /protest/index.php');
}


// *** Erreur dans la connexion ***
if (isset($_GET['loginError'])) {
    echo '
        <div class="alert alert-danger text-center" role="alert">
            Error in your login. Please try again.
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-3 text-center mb-2">
                    <form method="POST" action="index.php" class="needs-validation">';
        include "view/login.php";
        echo '
                    </form>
                </div>
            </div>
        </div>

    ';
}
?>


  <!-- Masthead -->
  <header class="masthead text-white text-center">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-xl-9 mx-auto">
          <h1 class="mb-5">Enjoy the safest way to authenticate your PDF files ! Download the software now.</h1>
        </div>
        <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
          <form>
            <div class="form-row justify-content-center">
              <div class="col-12 col-md-3">
                  <a class="btn btn-primary btn-lg" href="./view/test.php" download> Download </a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </header>

  <!-- Icons Grid -->
  <section class="features-icons bg-light text-center">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
            <div class="features-icons-icon d-flex">
              <i class="icon-screen-desktop m-auto text-primary"></i>
            </div>
            <h3>Easy to access</h3>
            <p class="lead mb-0">Fully supported WEB and desktop version!</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
            <div class="features-icons-icon d-flex">
              <i class="icon-layers m-auto text-primary"></i>
            </div>
            <h3>Easy to store</h3>
            <p class="lead mb-0">Simply scan your pdf to add them to your database!</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="features-icons-item mx-auto mb-0 mb-lg-3">
            <div class="features-icons-icon d-flex">
              <i class="icon-check m-auto text-primary"></i>
            </div>
            <h3>Easy to scan</h3>
            <p class="lead mb-0">Scan the QR codes at the bottom to verify the authenticity!</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <?php include ("./struct/footer.php");?>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
