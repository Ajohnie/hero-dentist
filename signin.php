<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Hero</title>
    <?php include 'includes/csslibs.php' ?>
    <link rel="stylesheet" href="static/css/signin.css" />
</head>
<body>
    <?php include 'includes/navbar.php' ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-5" id="signin-panel">
                <h4>Sign In</h4>
                <form action="" method="POST"> 
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
                            <button type="submit" class="btn btn-sm w-100 btn-dark bg-theme-primary">Sign In</button>
                        </div>
                    </div>
                </form>
                <p class="text-center"><a href="">Forgot your password?</a></p>
            </div>
        </div>
    </div>
   
    <?php include 'includes/jslibs.php' ?>
</body>
</html>