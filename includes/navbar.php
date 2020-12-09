<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">
        <img src="static/images/logo.png"> Dental Hero
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="signup.php">Sign Up</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="signin.php">
                    <button class="btn btn-info btn-sm">Sign In</button>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <button class="btn btn-info btn-sm">Sign Out</button>
                </a>
            </li>
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
    .navbar .btn-info{
        background: var(--primary-color);
        border-color: var(--primary-color)!important;
    }
</style>