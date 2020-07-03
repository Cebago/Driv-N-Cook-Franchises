<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="collapse navbar-collapse col-md-5 mx-auto" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <?php if ($_SERVER["REQUEST_URI"] != './home.php') { ?>
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Accueil</a>
                </li>
            <?php } ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="global" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    Mon camion
                </a>
                <div class="dropdown-menu" aria-labelledby="global">
                    <a class="dropdown-item" href="truckInfo.php">Information</a>
                    <a class="dropdown-item" href="truckMaintenance.php">Carnet d'entretien</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="warehouses" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    Mon stock
                </a>
                <div class="dropdown-menu" aria-labelledby="warehouses">
                    <a class="dropdown-item" href="#">Historique</a>
                    <a class="dropdown-item" href="chooseWarehouse.php">Commandes</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="benefits" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    Mes événements
                </a>
                <div class="dropdown-menu" aria-labelledby="benefits">
                    <a class="dropdown-item" href="#">Créer un nouvel évènement</a>
                    <a class="dropdown-item" href="#">Réservations</a>
                    <a class="dropdown-item" href="#">Dégustations</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="cart.php" id="benefits" role="button"
                   aria-haspopup="true" aria-expanded="false">
                    <span class="badge badge-pill badge-dark" id="cartQty">
                        <i class="fas fa-shopping-cart"></i>
                        <?php
                        $cart = lastCart($_SESSION["email"]);
                        $pdo = connectDB();
                        $queryPrepared = $pdo->prepare("SELECT SUM(quantity) AS count FROM CARTINGREDIENT, CART, USER WHERE idUser = user AND cart = idCart AND emailAddress = :user AND idCart = :cart");
                        $queryPrepared->execute([
                                ":user" => $_SESSION["email"],
                                ":cart" => $cart
                        ]);
                        $result = $queryPrepared->fetch(PDO::FETCH_ASSOC);
                        $count = $result["count"];
                        if (!empty($count)) {
                            echo " " . $count;
                        } else {
                            echo " 0";
                        }
                        ?>
                    </span>
                </a>
            </li>
        </ul>
    </div>
    <div class="float-right">
        <div class="form-inline my-2 my-lg-0 dropdown">
            <a class="btn btn-dark my-2 my-sm-0" href="#" id="warehouses" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <i class="far fa-user-circle"></i>&nbsp;Gérer mon compte
            </a>
            <div class="dropdown-menu dropdown-menu-lg-left" aria-labelledby="warehouses">
                <a class="dropdown-item" href="myProfile.php">Mon profil</a>
                <a class="dropdown-item" href="myPassword.php">Mot de passe</a>
                <a class="dropdown-item" href="functions/logout.php"><i class="fas fa-sign-out-alt"></i>&nbsp;Déconnexion</a>
            </div>
        </div>
    </div>
</nav>