<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container d-flex justify-content-between">
        <a href="#" class="navbar-brand d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
                 stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="mr-2"
                 viewBox="0 0 24 24" focusable="false">
                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                <circle cx="12" cy="13" r="4"/>
            </svg>
            <strong class="col-md-3" href="index_client.php">Driv'n Cook</strong>
        </a>
        <div class="dropdown col-md-3">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="truckInfo"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Mon camion
            </button>
            <div class="dropdown-menu" aria-labelledby="truckInfo">
                <a class="dropdown-item" href="truckInfo.php">Information</a>
                <a class="dropdown-item" href="#">Carnet d'entretien</a>
            </div>
        </div>
        <div class="dropdown col-md-3">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Mon stock &nbsp
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <a class="dropdown-item" type="button">Historique</a>
                <a class="dropdown-item" type="button">Commandes</a>
            </div>
        </div>
        <div class="dropdown col-md-3">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Mes événements
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <a class="dropdown-item" type="button">Réservations</a>
                <a class="dropdown-item" type="button">Dégustations</a>
            </div>
        </div>
    </div>
</nav>