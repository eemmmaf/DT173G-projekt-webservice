<?php
include('includes/settings.php');

if (!isset($_SERVER['HTTP_TOKEN'])) {
    $response = array('message' => 'Ingen token finns');
    http_response_code(401);
    exit;
} else {
    $token = $_SERVER['HTTP_TOKEN'];
    $user = new User();

    if(!$user->validateToken($token)){
        $response = array('message' => 'Invalid token');
        json_encode($response);
        http_response_code(403);
        exit;
    }
}




//Om en parameter av code finns i urlen lagras det i en variabel
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
}

//Skapar en instans av klassen booking
$booking = new Booking();


switch ($method) {
    case 'GET':

        if (isset($booking_id)) {
            $response = $booking->getBookingById($booking_id);
        } else {
            $response = $booking->getBookings();
        }
        

        if (count($response) === 0) {
            $response = array("message" => "Det finns inga bokningar lagrade i databasen");
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
        if (!$booking->setBooking($data['booking_date'], $data['booking_time'], $data['guest_fname'], $data['guest_ename'], $data['guest_email'], $data['guest_text'], $data['quantity'])) {
            $success = false;
            $response = array("message" => "Kontrollera fälten och försök igen");
            http_response_code(400); //400 = Bad request för ej korrekt inmatning
        }

        if ($success = true) {
            if ($booking->addBooking($data['booking_date'], $data['booking_time'], $data['guest_fname'], $data['guest_ename'], $data['guest_email'], $data['guest_text'], $data['quantity'])) {
                $response = array("message" => "Tack för din bokning!");
                http_response_code(201); //201 = Created success
            } else {
                http_response_code(500);
                $response = array("message" => "Fel vid bokning");
            }
        }
        break;


    case 'PUT':
        //Läser in JSON-data skickad med anropet och omvandlar till ett objekt.
        $data = json_decode(file_get_contents("php://input"), true);

        $success = true; //Variabel för när det postade är OK
        if (!$booking->setBooking($data['booking_date'], $data['booking_time'], $data['guest_fname'], $data['guest_ename'], $data['guest_email'], $data['guest_text'], $data['quantity'])) {
            $success = false;
            $response = array("message" => "Fyll i fälten");
            http_response_code(400); //400 = Bad request för ej korrekt inmatning
        }

        if ($success = true) {
            if ($booking->updateBooking($booking_id, $data['booking_date'], $data['booking_time'], $data['guest_fname'], $data['guest_ename'], $data['guest_email'], $data['guest_text'], $data['quantity'])) {
                $response = array("message" => "Bokningen har uppdaterats");
                http_response_code(200); //200 = OK request
            } else {
                http_response_code(500);
                $response = array("message" => "Fel vid uppdatering");
            }
        }

        break;
    case 'DELETE':
        if (!isset($booking_id)) {
            http_response_code(400);
            $response = array("message" => "Ingen maträtt har valts. Välj den maträtt som ska raderas");
        } else {
            if ($booking->deleteBooking($booking_id))
                http_response_code(200); //Lyckad borttagning
            $response = array("message" => "Maträtten har raderats från databasen");
        }
        break;
}

//Skickar svar tillbaka till avsändaren
echo json_encode($response);