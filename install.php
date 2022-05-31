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
    drink_description TEXT,
    drink_price INT(11), 
    drink_category_id INT(11) NOT NULL
    ); 
";

$sql .= "DROP TABLE IF EXISTS food;";

$sql .= "CREATE TABLE food(
    food_id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    food_name VARCHAR(128) NOT NULL,
    food_description TEXT,
    food_price INT(11),
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
    guest_text TEXT NULL DEFAULT 'Inget önskemål',
    quantity INT(2) NOT NULL
    );";

$sql .= "DROP TABLE IF EXISTS food_category, drink_category, food_type, drink_type;";

$sql .= "CREATE TABLE food_category(
    food_category_id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    food_category_name VARCHAR(128) NOT NULL);

        CREATE TABLE drink_category(
        drink_category_id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
        drink_category_name VARCHAR(128) NOT NULL);
        
        CREATE TABLE food_type(
        food_type_id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
        food_type_name VARCHAR(128) NOT NULL);
        ;";

//Lägger till foreign keys till mat och drink

$sql .=
    "ALTER TABLE food
ADD FOREIGN KEY (food_category_id) REFERENCES food_category(food_category_id) ON UPDATE CASCADE;

ALTER TABLE food
ADD FOREIGN KEY(food_type_id) REFERENCES food_type(food_type_id) ON UPDATE CASCADE;

ALTER TABLE drink
ADD FOREIGN KEY (drink_category_id) REFERENCES drink_category(drink_category_id) ON UPDATE CASCADE;
";

$sql .= "DROP TABLE IF EXISTS tokens, user;";

$sql .= "CREATE TABLE user(
    username VARCHAR(128) NOT NULL PRIMARY KEY,
    password VARCHAR(128),
    token VARCHAR(128))";


echo "<pre> $sql </pre>";

//SKicka till servern
if ($db->multi_query($sql)) {
    echo "Tabeller installerade";
} else {
    "Fel vid installation av tabell";
}
