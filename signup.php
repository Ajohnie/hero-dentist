<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Hero</title>
    <?php include 'includes/csslibs.php' ?>
    <link rel="stylesheet" href="static/css/signup.css" />
</head>
<body>
    <?php include 'includes/navbar.php' ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-5" id="signup-panel">
                <h4>Sign Up</h4>
                <form action="">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <input type="text" placeholder="Your name" class="form-control" >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <input type="email" placeholder="Email" class="form-control" >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <input type="password" placeholder="Password" class="form-control" >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <button type="submit" class="btn btn-sm w-100 btn-dark bg-theme-primary">Sign Up</button>
                        </div>
                    </div>
                </form>
                <p class="text-center">By signing up you agree to our<br><a href="">Terms & Conditions</a></p>
            </div>
        </div>
    </div>
   
    <?php include 'includes/jslibs.php' ?>
</body>
</html>