<?php
include('config.php');
//Anslut
$db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
if ($db->connect_errno > 0) {
    die("Fel vid anslutning" . $db->connect_error);
}

//SQL-fråga
$sql = "DROP TABLE IF EXISTS drink;";

$sql .= "
CREATE TABLE drink(
    drink_id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    drink_name VARCHAR(128) NOT NULL,
    drink_description TEXT NOT NULL,
    drink_price VARCHAR(128) NOT NULL, 
    drink_category_id INT(11) NOT NULL
    ); 
";

$sql .= "DROP TABLE IF EXISTS food;";

$sql .= "CREATE TABLE food(
    food_id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    food_name VARCHAR(128) NOT NULL,
    food_description TEXT NOT NULL,
    food_price VARCHAR(128) NOT NULL,
    food_category_id INT(11) NOT NULL,
    food_type_id INT(11) NOT NULL
    );";

$sql .= "DROP TABLE IF EXISTS booking;";

$sql .= "CREATE TABLE booking(
    booking_id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    booking_date VARCHAR(128) NOT NULL,
    booking_time VARCHAR(128) NOT NULL,
    guest_fname varchar(128) NOT NULL,
    guest_ename varchar(128) NOT NULL,
    guest_email varchar(128) NOT NULL,
    guest_text TEXT,
    quantity INT(2) NOT NULL
    );";

$sql .= "DROP TABLE IF EXISTS food_category, drink_category, food_type, drink_type;";

$sql .= "CREATE TABLE food_category(
    food_category_id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    food_category_name VARCHAR(128) NOT NULL);";

$sql .= "CREATE TABLE drink_category(
        drink_category_id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
        drink_category_name VARCHAR(128) NOT NULL);";

$sql .= "CREATE TABLE food_type(
        food_type_id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
        food_type_name VARCHAR(128) NOT NULL);";

$sql .= "DROP TABLE IF EXISTS tokens, user;";

$sql .= "CREATE TABLE user(
    username VARCHAR(128) NOT NULL PRIMARY KEY,
    password VARCHAR(128));";

//Lägger till foreign keys till mat och drink

$sql .=
    "ALTER TABLE food
ADD FOREIGN KEY (food_category_id) REFERENCES food_category(food_category_id) ON UPDATE CASCADE;";

$sql .= "ALTER TABLE food
ADD FOREIGN KEY(food_type_id) REFERENCES food_type(food_type_id) ON UPDATE CASCADE;";

$sql .= "ALTER TABLE drink
ADD FOREIGN KEY (drink_category_id) REFERENCES drink_category(drink_category_id) ON UPDATE CASCADE;
";

//Gör en insert till kateogierna och typerna så att de hamnar i rätt ordning vid installering
$sql .= "INSERT INTO food_category (food_category_id, food_category_name) VALUES (1, 'Förrätt'), (2, 'Varmrätt'), (3, 'Efterrätt');";
$sql .= "INSERT INTO food_type(food_type_id, food_type_name) VALUES (1,'Grill'), (2, 'Pasta'), (3, 'Pizza'), (4, 'Sött'), (5, 'Förrätt');";
$sql .= "INSERT INTO drink_category (drink_category_id, drink_category_name) VALUES ('1','Vitt vin'), (2, 'Rött vin'), (3, 'Öl'), (4, 'Alkoholfritt');";

echo "<pre> $sql </pre>";

//SKicka till servern
if ($db->multi_query($sql)) {
    echo "Tabeller installerade";
} else {
    "Fel vid installation av tabell";
}
