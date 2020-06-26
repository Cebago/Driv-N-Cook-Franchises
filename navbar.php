<navbar class="mb-5">
    <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container d-flex justify-content-between">
            <a href="#" class="navbar-brand d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
                     stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="mr-2"
                     viewBox="0 0 24 24" focusable="false">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                    <circle cx="12" cy="13" r="4"/>
                </svg>
                <strong class="col-md-3" href="home.php">Driv'n Cook</strong>

                <div class="dropdown col-md-3">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Mon camion
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <button class="dropdown-item" type="button" onclick=window.location.href='#'>Information</button>
                        <button class="dropdown-item" type="button" onclick=window.location.href='#'>Carnet d'entretien</button>
                    </div>
                </div>
                <div class="dropdown col-md-3">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Mon stock &nbsp
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <button class="dropdown-item" type="button">Historique</button>
                        <button class="dropdown-item" onclick=window.location.href='chooseWarehouse.php' type="button">
                            Commandes
                        </button>
                    </div>
                </div>
                <div class="dropdown col-md-3">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Mes événements
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <button class="dropdown-item" type="button">Réservations</button>
                        <button class="dropdown-item" type="button">Dégustations</button>
                    </div>
                </div>
                <div class="dropdown col-md-3">
                    <button></button>
                </div>
            </a>
        </div>
    </div>
</navbar>