# DENTAL HERO

Dental Hero, an Amazon Alexa skill (app) that will assist dentists in their clinics.
Dentists are going to use their voices to interact with this app that will be installed-
in Amazon smart speaker, echo. Dental Hero will offer voice assistance to dentists and dental-
students by providing diagnosis, appointments management, review patients' records and much more.

## FOLDER STRUCTURE

It is organised as an MVC(model-view-controller) application. Why MVC ?.
MVC allows separation of concerns which reduces overall software complexity.
This enables production of quality code. The project can also be easily subdivided
between teams of engineers, each concentrating on a particular area.
Front-end for UI/UX teams, and Backend For backend engineers and data scientists

All Data transactions are handled by models.
The models folder includes a connections.php file which contains logic for connecting to the datasource.
We are using firebase but the file can be easily refactored to use other data technologies(MYSQL for example).
The point however, is that the controllers and the views should be blind about the data store implementation-
we choose.

The controllers filter and pass data between the views and the models.
The views are responsible for rendering formatted HTML or Any other Output to the user. Also, the rendering
technology(html in this case) can be changed with out affecting the models and with slight-
modification to the controllers

## DATA VALIDATION
Data validation occurs in 3 phases;
  1) Using input masks and restrictions in the html forms in views.
  2) Using preliminary filters in controllers. This is where request objects are handled.
  3) In the models. This is the main point of validation. Any data that does not meet-
     the applications data policy is not accepted by the data store handlers

## DATA STORE
Data is stored in Google Cloud Firestore and is synchronized with Google Alexa in the dental clinic.

## USE OF CONSTANTS
Most of the variable are called using constants, except in a couple of cases where constants could not be used
due to namespace limitations (e.g in the case of loggedInUser). The constants enable replacing of certain files
without need for making system wide changes. Also, they make testing the application easier. A test case for a
file can be written and easily used by just pointing to the path to the test file.
