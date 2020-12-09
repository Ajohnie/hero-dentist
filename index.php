<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Hero</title>
    <?php include 'includes/csslibs.php' ?>
    <link rel="stylesheet" href="static/css/index.css" />
</head>
<body>
    <?php include 'includes/navbar.php' ?>

    <div class="container bg-light py-3 mt-4" id="intro-container">
        <div class="row">
            <div class="col-6 offset-1 d-flex flex-column justify-content-center">
                <p class="text-justify">Dental Hero, an Amazon Alexa skill (app) that will assist dentists in their clinics. Dentists are going to use their voices to interact with this app that will be installed in Amazon smart speaker, echo.</p>
                <p class="text-justify">Dental Hero will offer voice assistance to dentists and dental students by providing diagnosis, appointments management, review patients' records and much more.</p>
            </div>
            <div class="col-4">
                <img src="static/images/echo_dot.png" class="img-fluid p-4">
            </div>
        </div>
    </div>

    <div class="container bg-light py-3 mt-4" id="features-container">
        <div class="row h-100">
            <div class="col-12 offset-2 pt-4">
                <h4>Features</h4>
                <ul>
                    <li>Review patient's records</li>
                    <li>Review appoinments</li>
                    <li>Provide suitable answers for complicated cases</li>
                </ul>
            </div>
        </div>
    </div>

    <?php include 'includes/jslibs.php' ?>
</body>
</html>