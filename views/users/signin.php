<?php include (dirname(__FILE__, 3)) . '/shared/constants.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include CSS ?>
    <link rel="stylesheet" href="<?= HOME . 'assets/css/signin.css' ?>"/>
</head>
<body>
<?php include NAV_BAR ?>
<?php // include USERS_CONTROLLER ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-5" id="signin-panel">
            <h4>Sign In</h4>
            <form action="<?= USERS_CONTROLLER ?>" method="POST" onsubmit="logIn(event)">
                <div class="form-row">
                    <div class="form-group col-12">
                        <input type="email" name="email" value="" placeholder="Email" required class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <input type="password" name="password" value="" required placeholder="Password" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <input type="submit" value="Sign In" name="signIn"
                               class="btn btn-sm w-100 btn-dark bg-theme-primary">
                    </div>
                </div>
            </form>
            <p class="text-center"><a href="">Forgot your password?</a></p>
        </div>
    </div>
</div>
<?php include JS ?>
<script>
    let controllerUrl = '';
    /** put this after calling main javascript libraries */
    function logIn(event) {
        event.preventDefault();
        const logInForm = $(event.target);
        const email = $('input[name ="email"]')[0].value;
        const password = $('input[name ="password"]')[0].value;
        const signIn = $('input[name ="signIn"]')[0].value; // point to signIn() function in controller
        makeAjaxRequest(logInForm.attr('action'), {signIn: signIn, email: email, password: password});
    }
</script>
</body>
</html>
