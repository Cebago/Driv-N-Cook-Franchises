<?php include "header.php" ?>
    <div class="container py-5">
        <!-- For demo purpose -->
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4">Payement</h1>
            </div>
        </div> <!-- End -->
        <div class="row mr-5">
            <div class="col-lg-6 mx-auto">
                <div class="card ">
                    <div class="card-header">
                        <div class="bg-white shadow-sm pt-4 pl-2 pr-2 pb-2">
                            <!-- Credit card form tabs -->
                            <ul role="tablist" class="nav bg-light nav-pills rounded nav-fill mb-4">
                                <li class="nav-item"><a data-toggle="pill" href="#credit-card" class="nav-link active ">
                                        <i class="fas fa-credit-card mr-2"></i> Carte de crédit </a></li>
                                <li class="nav-item"><a data-toggle="pill" href="#paypal" class="nav-link "> <i
                                                class="fas fa-wallet mr-2"></i> Espèce </a></li>
                                <!--<li class="nav-item"> <a data-toggle="pill" href="#net-banking" class="nav-link "> <i class="fas fa-mobile-alt mr-2"></i> Net Banking </a> </li>-->
                            </ul>
                        </div> <!-- End -->
                        <!-- Credit card form content -->
                        <div class="tab-content">
                            <!-- credit card info-->
                            <div id="credit-card" class="tab-pane fade show active pt-4">
                                <form role="form">
                                    <div class="form-group"><label for="username">
                                            <h6>Card Owner</h6>
                                        </label> <input type="text" name="username" placeholder="Card Owner Name"
                                                        required class="form-control "></div>
                                    <div class="form-group"><label for="cardNumber">
                                            <h6>Card number</h6>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" name="cardNumber"
                                                   placeholder="Valid card number"
                                                   class="form-control " required>
                                            <div class="input-group-append">
                                                <span class="input-group-text text-muted">
                                                    <i class="fab fa-cc-visa mx-1"></i>
                                                    <i class="fab fa-cc-mastercard mx-1"></i>
                                                    <i class="fab fa-cc-amex mx-1"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="form-group"><label><span class="hidden-xs">
                                                    <h6>Expiration Date</h6>
                                                </span></label>
                                                <div class="input-group">
                                                    <input type="number" placeholder="MM" name="" class="form-control"
                                                           required>
                                                    <input type="number" placeholder="YY" name="" class="form-control"
                                                           required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group mb-4">
                                                <label data-toggle="tooltip"
                                                       title="Three digit CV code on the back of your card">
                                                    <h6>CVV <i class="fa fa-question-circle d-inline"></i>
                                                    </h6>
                                                </label>
                                                <input type="text" required class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" class="subscribe btn btn-primary btn-block shadow-sm">
                                            Confirm Payment
                                        </button>
                                </form>
                            </div>
                        </div> <!-- End -->
                        <!-- Paypal info -->
                        <div id="paypal" class="tab-pane fade pt-4">
                            <h6 class="pb-2">Select your paypal account type</h6>
                            <div class="form-group ">
                                <label class="radio-inline">
                                    <input type="radio" name="optradio" checked>
                                    Domestic
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="optradio" class="ml-5">
                                    International
                                </label>
                            </div>
                            <p>
                                <button type="button" class="btn btn-primary "><i class="fab fa-paypal mr-2"></i> Log
                                    into my Paypal
                                </button>
                            </p>
                            <p class="text-muted"> Note: After clicking on the button, you will be directed to a secure
                                gateway for payment. After completing the payment process, you will be redirected back
                                to the website to view details of your order. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mb-4">
            <div class="col-lg-8 mx-auto text-center">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Mon panier</span>
                    <span class="badge badge-secondary badge-pill">3</span>
                </h4>
            </div>
            <div class="col-lg-6 mx-auto">
                <div class="card ">
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">Product name</h6>
                            <small class="text-muted">Brief description</small>
                        </div>
                        <span class="text-muted">$12</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">Second product</h6>
                            <small class="text-muted">Brief description</small>
                        </div>
                        <span class="text-muted">$8</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">Third item</h6>
                            <small class="text-muted">Brief description</small>
                        </div>
                        <span class="text-muted">$5</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <div class="text-success">
                            <h6 class="my-0">Promo code</h6>
                            <small>EXAMPLECODE</small>
                        </div>
                        <span class="text-success">-$5</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total (USD)</span>
                        <strong>$20</strong>
                    </li>
                </div>
            </div>
        </div>
    </div>


<?php include "footer.php" ?>