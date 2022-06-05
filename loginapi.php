<?php
include('includes/config.php');
include('includes/settings.php');

//Skapar instans av klassen User
$user = new User();


//Om annan metod än POST skickats skickas felmeddelande och avslutar scriptet med exit
if($method != "POST") {
    http_response_code(405); //Method not allowed
    $response = array("message" => "Endast metoden POST tillåts");
    echo json_encode($response);
    exit;
}

//Omvandlar body från JSON
$data = json_decode(file_get_contents("php://input"), true);

//Kontroll att username och password skickats med
if(isset($data["username"]) && isset($data["password"])) {
    $username = $data["username"];
    $password = $data["password"];
} else {
    http_response_code(400); //Bad request
    $response = array("message" => "Skicka med användarnamn och lösenord");
    echo json_encode($response);
    exit;
}

//Kontrollerar att användarnamn och lösenord är giltiga
if($user->logIn($username, $password)) {
    $response = array("message" => "Du är inloggad", "user" => true);
    http_response_code(200); //Ok
} else {
    $response = array("message" => "Felaktigt användarnamn eller lösenord");
    http_response_code(401); //Unauthorized
}

//Skickar svar tillbaka till avsändaren
echo json_encode($response);
