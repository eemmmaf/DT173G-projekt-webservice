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

//Om en parameter av code finns i urlen lagras det i en variabel
if (isset($_GET['drink_id'])) {
    $drink_id = $_GET['drink_id'];
}

if(isset($_GET['drink_category_id'])) {
    $drink_category = $_GET['drink_category_id'];
}

//Skapar en instans av klassen drink
$drink = new Drink;


switch ($method) {
    case 'GET':

        if (isset($drink_id)) {
            $response = $drink->getDrinkById($drink_id);
        } else {
            $response = $drink->getDrinks();
        }

        if(isset($drink_category)) {
            $response = $drink->getDrinkByCategory($drink_category);
        }else{
            $response = $drink->getDrinks();
        }

        if (count($response) === 0) {
            $response = array("message" => "Det finns inga drycker lagrade i databasen");
            http_response_code(404); // Kod 404 - Not found
        } else {
            http_response_code(200); //Kod 200 = OK
        }
        break;

        //Post
    case 'POST':
        //Läser in JSON-data skickad med anropet och omvandlar till ett objekt.
        $data = json_decode(file_get_contents("php://input"), true);

        $success = true; //Variabel för när det postade är OK
        if (!$drink->setDrink($data['drink_name'], $data['drink_description'], $data['drink_price'], $data['drink_category_id'])) {
            $success = false;
            $response = array("message" => "Fyll i fälten");
            http_response_code(400); //400 = Bad request för ej korrekt inmatning
        }

        if ($success = true) {
            if ($drink->addDrink($data['drink_name'], $data['drink_description'], $data['drink_price'], $data['drink_category_id'])) {
                $response = array("message" => "Drycken har lagrats");
                http_response_code(201); //201 = Created success
            } else {
                http_response_code(500);
                $response = array("message" => "Fel vid lagring");
            }
        }
        break;


    case 'PUT':
        //Läser in JSON-data skickad med anropet och omvandlar till ett objekt.
        $data = json_decode(file_get_contents("php://input"), true);

        $success = true; //Variabel för när det postade är OK

        if (!$drink->setDrink($data['drink_name'], $data['drink_description'], $data['drink_price'], $data['drink_category_id'])) {
            $success = false;
            $response = array("message" => "Fyll i alla fält");
            http_response_code(400); //400 = Bad request för ej korrekt inmatning
        }

        if ($success = true) {
            if ($drink->updateDrink($drink_id, $data['drink_name'], $data['drink_description'], $data['drink_price'], $data['drink_category_id'])) {
                $response = array("message" => "Drycken har uppdaterats");
                http_response_code(200); //200 = OK request
            } else {
                http_response_code(500);
                $response = array("message" => "Fel vid uppdatering");
            }
        }

        break;
    case 'DELETE':
        if (!isset($drink_id)) {
            http_response_code(400);
            $response = array("message" => "Ingen dryck har valts. Välj den dryck som ska raderas");
        } else {
            if ($drink->deleteDrink($drink_id))
                http_response_code(200); //Lyckad borttagning
            $response = array("message" => "Drycken har raderats från databasen");
        }
        break;
}

//Skickar svar tillbaka till avsändaren
echo json_encode($response);