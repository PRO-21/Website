<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="This is the signing up page">
    <meta name="author" content="Simeunovic, Viotti">

    <title>Sign Up</title>

    <!-- Bootstrap core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template -->
    <link href="../css/landing-page.min.css" rel="stylesheet">

</head>

<body>
<?php
include_once ("../utils/jwtUtils.php");

if (isset($_COOKIE[loginCookieName])) { // Si l'utilisateur a une session ouverte, cette page est bloquée
    header('Location: /protest/index.php');
}

include ("../struct/header.php");


// *** Récupération des pays ***
$url = 'https://pro.simeunovic.ch:8022/protest/api/country';
$ch = curl_init($url);

// Paramètres
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json
                                                       Location: /country'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = json_decode(curl_exec($ch), true);
curl_close($ch);


// *** Création d'un compte utilisateur ***
if (isset($_POST['signUp'])) {
    // Récupération des data
    $nom = $_POST['firstName'];
    $prenom = $_POST['lastName'];
    $email = $_POST['email'];
    $country = $_POST['countrySelect'];
    $ville = $_POST['ville'];
    $npa = $_POST['npa'];
    $adresse = $_POST['adresse'];
    $motDepasse = $_POST['password'];

    // Récupération de l'id pays depuis la variable country
    $bracketPos = strpos($country, "[");
    $idCountry = substr($country, $bracketPos + 1, -1);

    // Création ressource cURL
    $url = 'https://pro.simeunovic.ch:8022/protest/api/user';
    $ch = curl_init($url);

    // Mise en forme des données en json
    $data = array(
        'prenom' => "$prenom",
        'nom' => "$nom",
        'adresse' => "$adresse",
        'npa' => "$npa",
        'email' => "$email",
        'motDePasse' => "$motDepasse",
        'idPersonnePays' => "$idCountry"
    );
    $payload = json_encode($data);

    // Paramètres
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if ($result['status']['code'] == 200) {
        echo
            '<div class="alert alert-success" role="alert">
                Account ' . $email . ' created with success !
            </div>';
    } else {
        echo
            '<div class="alert alert-danger" role="alert">
                Error. ' . $result['status']['message'] . '
            </div>';
    }

}
?>



<form method="POST" action="" class="needs-validation">
    <div class="justify-content-center container">
        <div class="form-group col-sm-3">
            <label for="firstName">First name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="John" required>
        </div>
        <div class="form-group col-sm-3">
            <label for="lastName">Last name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Smith" required>
        </div>
        <div class="form-group col-sm-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email" required>

        </div>
        <div class="form-group col-sm-3">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
        </div>
        <div class="form-group col-sm-3">
            <label for="countrySelect">Country</label>
            <select class="form-control" id="countrySelect" name="countrySelect">
                <?php
                foreach($result['data'] as $pays){
                    echo " 
                        <option>" .  $pays['nomPays'] . " [" . $pays['idPays'] . "]</option>"
                    ;
                }
                ?>
            </select>
        </div>
        <div class="form-group col-sm-3">
            <label for="ville">City</label>
            <input type="text" class="form-control" id="ville" name="ville" placeholder="Lausanne" required>
        </div>
        <div class="form-group col-sm-3">
            <label for="npa">Zip</label>
            <input type="text" class="form-control" id="npa" name="npa" placeholder="1003" required>
        </div>
        <div class="form-group col-sm-3">
            <label for="adresse">Address</label>
            <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Rue de Bourg 5" required>
        </div>
        <button class="btn btn-primary justify-content-end" type="submit" name="signUp">Sign up</button>
    </div>

</form>
<?php include ("../struct/footer.php");?>
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>




