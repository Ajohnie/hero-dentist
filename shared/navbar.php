<?php
$userIsLoggedIn = isset($_SESSION['LoggedInUser']);
?>
<?php if (!$userIsLoggedIn): ?>
    <script>
        const currentLocation = window.location.href;
        const urlForbidden = currentLocation.indexOf('appointment') > -1 || currentLocation.indexOf('patient') > -1 || currentLocation.indexOf('dentist-list') > -1 || currentLocation.indexOf('add-dentist') > -1;
        if (urlForbidden) {
            window.location = '<?= SIGN_IN ?>';
        }
    </script>
<?php endif; ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">
        <img src="<?= LOGO_URL ?>"> Dental Hero
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <?php if (!$userIsLoggedIn): ?>
                <li class="nav-item active">
                    <a class="nav-link" href="<?= HOME ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= SIGN_UP ?>">Sign Up</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= SIGN_IN ?>">
                        <button class="btn btn-info btn-sm">Sign In</button>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($userIsLoggedIn): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= USERS_CONTROLLER ?>"
                       onclick="logout(this.getAttribute('href'))">
                        <button class="btn btn-info btn-sm">Sign Out</button>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<style>
    .navbar {
        height: 50px;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        z-index: 900
    }

    .navbar-brand {
        padding: 0px;
    }

    .navbar-brand img {
        height: 40px
    }

    .navbar .btn-info {
        background: var(--primary-color);
        border-color: var(--primary-color) !important;
    }
</style>
