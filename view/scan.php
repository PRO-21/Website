<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="This is the page presenting the company">
    <meta name="author" content="Simeunovic, Viotti">

    <title>Résultat du scan</title>

    <!-- Bootstrap core CSS -->
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="/vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template -->
    <link href="/css/landing-page.min.css" rel="stylesheet">

</head>

<body>
<?php include ("../struct/header.php")?>

<?php
//Récupération de l'id du certificat via la méthode get
$id = $_GET['id'];
$url = "https://pro.simeunovic.ch:8022/api/cert/".$id;

//Tentative d'accès à l'API avec l'id du certificat
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
// This is what solved the issue (Accepting gzip encoding)
curl_setopt($ch, CURLOPT_ENCODING, "utf-8");
$response = curl_exec($ch);
curl_close($ch);

//Traitement du JSON en retour
$obj = json_decode($response);
$status = $obj->{"status"};
$code = $status->{"code"};

if($code == 404)
{
    echo "La page n'a pas pu être trouvée, redirection\n";
    echo "<script>setTimeout(\"location.href = 'https://pro.simeunovic.ch:8022/';\",1500);</script>";
}
else if($code==200)
{
    $data=$obj->{"data"};
    $champs=$data->{"champs"};

    $idCertificat=$data->{"idCertificat"};
    $dateSignature=$data->{"dateSignature"};
    $signataire=$data->{"signataire"};

    $nom=$signataire->{"nom"};
    $prenom=$signataire->{"prenom"};
    echo '<h1 align="center">Résultat du scan</h1>';
    echo '<div class="container">';
    echo '<h2>Informations sur le signataire</h2>';
    echo '<form class="justify-content-center container">';
    echo '<div class="form-group">';
    echo '<label for="idCertificat">Numéro de certificat</label>';
    echo '<input type="text" class="form-control" id="idCertificat" value="'.$idCertificat.'"disabled>';
    echo '</div>';
    echo '<div class="form-group">';
    echo '<label for="dateSignature">Date de la signature</label>';
    echo '<input type="text" class="form-control" id="dateSignature" value="'.$dateSignature.'" disabled>';
    echo '</div>';
    echo '<div class="form-group">';
    echo '<label for="prenomSignataire">Prénom du signataire</label>';
    echo '<input type="text" class="form-control" id="prenom" value="'.$prenom.'"disabled>';
    echo '</div>';
    echo '<div class="form-group">';
    echo '<label for="nomSignataire">Nom du signataire</label>';
    echo '<input type="text" class="form-control" id="nom" value="'.$nom.'" disabled>';
    echo '</div>';
    echo '</form>';
    echo '</div>';


    echo '<div class="container">';
    echo '<h2>Informations contenues dans le PDF</h2>';
    echo '<form class="justify-content-center container">';
    foreach($champs as $item)
    {
        $nomChamp=$item->{"nom"};
        $valeurChamp=$item->{"valeur"};
        echo '<div class="form-group">';
        echo '<label for="nom">'.$nomChamp.'</label>';
        echo '<input type="text" class="form-control" id="nom" value="'.$valeurChamp.'" disabled>';
        echo '</div>';

    }
    echo '</form>';
    echo '</div>';
}
?>

<?php include ("../struct/footer.php")?>
<script src="/vendor/jquery/jquery.min.js"></script>
<script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html><?php
