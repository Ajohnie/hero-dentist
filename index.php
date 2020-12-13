<?php include (dirname(__FILE__, 1)) . '/shared/constants.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include CSS ?>
    <link rel="stylesheet" href="assets/css/index.css"/>
</head>
<body>
<?php include NAV_BAR ?>

<div class="container bg-light py-3 mt-4" id="intro-container">
    <div class="row">
        <div class="col-6 offset-1 d-flex flex-column justify-content-center">
            <p class="text-justify">Dental Hero, an Amazon Alexa skill (app) that will assist dentists in their clinics.
                Dentists are going to use their voices to interact with the skill that will be installed in Amazon smart
                speaker, echo. The skill will help towards make dentist routine task more efficient by offering them
                information about endodontic problems applications through a voice interface.</p>
        </div>
        <div class="col-4">
            <img src="<?= ECHO_DOT_IMAGE_URL ?>" class="img-fluid p-4">
        </div>
    </div>
</div>

<div class="container bg-light py-3 mt-4" id="features-container">
    <div class="row h-100">
        <div class="col-12 offset-2 pt-4">
            <h4>Features</h4>
            <ul>
                <li>Provide suitable answers for complicated cases</li>
            </ul>
        </div>
    </div>
</div>
<script>
    let controllerUrl = '';
</script>
<?php include JS ?>
</body>
</html>
