<?php
include_once('config.php');
/*Headers med inställningar för din REST webbtjänst*/

//Använder asterisk så att webbtjänsten går att komma åt från alla domäner
header('Access-Control-Allow-Origin: *');

//Skickar datan i json-format
header('Content-Type: application/json');

//Metoderna som accepteras
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');

//Vilka headers som är tillåtna vcode anrop från klient-scodean, kan bli problem med CORS (Cross-Origin Resource Sharing) utan denna.
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

//Läser in vilken metod som skickats och lagrar i en variabel
$method = $_SERVER['REQUEST_METHOD'];
