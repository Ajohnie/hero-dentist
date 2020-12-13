<?php include (dirname(__FILE__, 3)) . '/shared/constants.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include CSS ?>
    <link rel="stylesheet" href="<?= HOME . 'assets/css/signup.css' ?>"/>
</head>
<body>
<?php include NAV_BAR ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-5" id="signup-panel">
            <h4>Sign Up</h4>
            <form action="<?= USERS_CONTROLLER ?>" method="POST" onsubmit="addUser(event)">
                <div class="form-row">
                    <div class="form-group col-12">
                        <input type="text" placeholder="Your name" required name="userName" id="userName" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <input type="email" placeholder="Email" required name="email" id="email" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <input type="password" placeholder="Password" required name="password" id="password"
                               class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <input type="submit" value="Sign Up" name="signUp"
                               class="btn btn-sm w-100 btn-dark bg-theme-primary">
                    </div>
                </div>
            </form>
            <p class="text-center">By signing up you agree to our<br><a href="">Terms & Conditions</a></p>
        </div>
    </div>
</div>

<?php include JS ?>
<script>
    let controllerUrl = '';
    /** put this after calling main javascript libraries */
    function addUser(event) {
        event.preventDefault();
        const signUpForm = $(event.target);
        const name = $('input[name ="userName"]')[0].value;
        const email = $('input[name ="email"]')[0].value;
        const password = $('input[name ="password"]')[0].value;
        const signUp = $('input[name ="signUp"]')[0].value; // point to signIn() function in controller
        makeAjaxRequest(signUpForm.attr('action'), {signUp: signUp, name: name, email: email, password: password});
    }
</script>
</body>
</html>
