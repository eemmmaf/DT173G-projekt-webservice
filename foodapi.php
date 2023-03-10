<?php
include('includes/config.php');
include('includes/settings.php');

//Om en parameter av code finns i urlen lagras det i en variabel
if (isset($_GET['food_id'])) {
    $food_id = $_GET['food_id'];
}


//Skapar en instans av klassen food
$food = new Food();


switch ($method) {
    case 'GET':

        if (isset($food_id)) {
            $response = $food->getFoodById($food_id);
        } else {
            $response = $food->getFood();
        }


        if (count($response) === 0) {
            $response = array("message" => "Det finns inga maträtter lagrade i databasen");
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
        if (!$food->setFood($data['food_name'], $data['food_description'], $data['food_price'], $data['food_category_id'], $data['food_type_id'])) {
            $success = false;
            $response = array("message" => "Kontrollera fälten och fyll i alla fälten");
            http_response_code(400); //400 = Bad request för ej korrekt inmatning
        }

        if ($success = true) {
            if ($food->addFood($data['food_name'], $data['food_description'], $data['food_price'], $data['food_category_id'], $data['food_type_id'])) {
                $response = array("message" => "Maträtten har lagrats");
                http_response_code(201); //201 = Created success
            } else {
                http_response_code(500);
                $response = array("message" => "Fel vid lagring. Kontrollera alla fält och försök igen");
            }
        }
        break;


    case 'PUT':
        //Läser in JSON-data skickad med anropet och omvandlar till ett objekt.
        $data = json_decode(file_get_contents("php://input"), true);

        $success = true; //Variabel för när det postade är OK

        if (!$food->setFood($data['food_name'], $data['food_description'], $data['food_price'], $data['food_category_id'], $data['food_type_id'])) {
            $success = false;
            $response = array("message" => "Fyll i alla fält");
            http_response_code(400); //400 = Bad request för ej korrekt inmatning
        }

        if ($success = true) {
            if ($food->updateFood($food_id, $data['food_name'], $data['food_description'], $data['food_price'], $data['food_category_id'], $data['food_type_id'])) {
                $response = array("message" => "Maträtten har uppdaterats");
                http_response_code(200); //200 = OK request
            } else {
                http_response_code(500);
                $response = array("message" => "Fel vid uppdatering");
            }
        }

        break;
    case 'DELETE':
        if (!isset($food_id)) {
            http_response_code(400);
            $response = array("message" => "Ingen maträtt har valts. Välj den maträtt som ska raderas");
        } else {
            if ($food->deletefood($food_id))
                http_response_code(200); //Lyckad borttagning
            $response = array("message" => "Maträtten har raderats från databasen");
        }
        break;
}

//Skickar svar tillbaka till avsändaren
echo json_encode($response);
