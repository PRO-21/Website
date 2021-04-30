<?php
    $siteRoot = dirname(__DIR__, 1);
    $documentRoot = $_SERVER['DOCUMENT_ROOT'];
    $siteRootSecure = str_replace("$documentRoot", "", $siteRoot); // Racine du site sans '/var/www/html'

    include_once ("$siteRoot" . "/utils/jwtUtils.php");

    if (isset($_COOKIE['loginSessionToken'])) {
        $name = decodeJWT($_COOKIE['loginSessionToken']); // Decodage du token JWT stocké en tant que cookie
    }


?>

<!-- Modal for login -->
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Log in</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?php echo $siteRootSecure . "/index.php" ?>" class="needs-validation">
                    <?php
                       include $siteRoot . "/view/login.php";
                    ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<nav class="navbar navbar-light bg-light static-top">
    <div class="container">
        <a class="navbar-brand" href="/protest/index.php">File authentificator</a>
        <?php
        if (isset($_COOKIE['loginSessionToken'])) {
            echo '
                        <b class="navbar-text"> Welcome ' . $name->fullname . ' </b>
                    ';
        }
        ?>
        <div class="header buttons">
            <?php
            if (isset($_COOKIE['loginSessionToken'])) {
                echo '
                    <a class="btn btn-info" href="/protest/index.php?logout">Logout</a>
                    ';
            } else {
                echo '
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#login">
                        Log In
                    </button>
                    <a class="btn btn-primary" href="/protest/view/signup.php">Sign up</a>
                ';
            }
            ?>
        </div>
    </div>
</nav>