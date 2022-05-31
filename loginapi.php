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


//Skapar en instans av klassen booking
$user = new User();
switch ($method) {
    case 'GET':
        http_response_code(200);
        if (isset($data['username'])) {
            $user->deleteToken($username);
            $response = array('message' => 'Utloggningen lyckades');
        } else {
            $response = array('message' => 'Utloggningen har misslyckats');
        }
        break;

        //Post
    case 'POST':
        //Läser in JSON-data skickad med anropet och omvandlar till ett objekt.
        $data = json_decode(file_get_contents("php://input"), true);

        //Kontrollerar inloggning
        if (isset($data['username'], $data['password'])) {
            if ($user->logIn($data['username'], $data['password'])) {
                //Genererar en token
                $token = $user->createToken($data['username']);
                $response = array('message' => 'Lyckad inloggning', "token" => $token);
                http_response_code(200); //Response code för lyckad inloggning
            } else {
                $response = array('status' => false, 'message' => 'Inloggning misslyckades');
                http_response_code(403); //Kod för misslyckad inloggning
            }
        }
        break;
}


//Skickar svar tillbaka till avsändaren
echo json_encode($response);
