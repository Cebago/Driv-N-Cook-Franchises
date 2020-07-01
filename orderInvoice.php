<?php
session_start();
require 'conf.inc.php';
require 'functions.php';

if (isConnected() && isActivated() && (isFranchisee() || isAdmin())) {


include "header.php";


$pdo = connectDB();
$queryPrepared = $pdo->prepare("SELECT firstname, lastname FROM USER WHERE idUser = 1");
$queryPrepared->execute();
$result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

?>

<?php foreach ($result as $value) { ?>
    <div class="card">
        <div class="card-body">

            <?php
            if (isset($_SESSION["errors"])) {
                echo "<div class='alert alert-danger'>";
                foreach ($_SESSION["errors"] as $error) {
                    echo "<li>" . $error;
                }
                echo "</div>";
            }
            unset($_SESSION["errors"]);
            ?>
            <form method="POST" action="orderVerify.php" enctype="multipart/form-data">
                <h5 class="card-header">Validation de la facturation</h5>
                <div class="card-body col-md-6">
                    <div class="form-group row">
                        <label for="inputFranchisee" class="col-sm-2 col-form-label">Franchisé</label>
                        <div class="col-sm-10">
                            <input type="text" name="franchisee" id="franchisee" class="form-control"
                                   placeholder="<?php echo $value['lastname'] ?> <?php echo $value['firstname'] ?>"
                                   value="<?php echo (isset($_SESSION["inputErrors"]))
                                       ? $_SESSION["inputErrors"]["franchisee"]
                                       : ""; ?>" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPrice" class="col-sm-2 col-form-label">Prix de la facture</label>
                        <div class="col-sm-10">
                            <div class="input-group-append col-md-3">
                                <input type="number" name="price" id="price" class="form-control"
                                       aria-label="Amount (to the nearest dollar)"
                                       value="<?php echo (isset($_SESSION["inputErrors"]))
                                           ? $_SESSION["inputErrors"]["price"]
                                           : ""; ?>">
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputFranchisee" class="col-sm-2 col-form-label">Date de la facturation</label>
                        <div class="col-sm-10  col-md-3">
                            <input type="date" name="date" id="date" class="form-control" placeholder="jj/mm/aaaa"
                                   value="<?php echo (isset($_SESSION["inputErrors"]))
                                       ? $_SESSION["inputErrors"]["date"]
                                       : ""; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleFormControlFile1">Choisir la facture</label>
                        <div class="col-sm-10">
                            <input type="file" name="invoice" id="invoice" class="form-control-file"
                                   value="<?php echo (isset($_SESSION["inputErrors"]))
                                       ? $_SESSION["inputErrors"]["invoice"]
                                       : ""; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </div>
                </div>
        </div>
    </div>
<?php } ?>
</form>


<?php include "footer.php"; ?>

<?php
} else {
    header("Location: login.php");
}?>