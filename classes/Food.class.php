<?php

class Food
{
    //Properties
    private $db; //Databas-anslutning
    private $food_id;
    private $food_name;
    private $food_description;
    private $food_price;
    private $food_category;
    private $food_type;

    //Konstruktor med databasanslutning
    function __construct()
    {
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);

        //Kontrollerar om det finns något fel
        if ($this->db->connect_errno > 0) {
            die("Fel vid anslutning" . $this->db->connect_error);
        }
    }

    // ----Set-metod---- //
    public function setFood(string $food_name, string $food_description, string $food_price, int $food_category, int $food_type): bool
    {
        if ($food_name && $food_description && $food_price && $food_category && $food_type != "") {
            $this->food_name = $food_name;
            $this->food_description = $food_description;
            $this->food_price = $food_price;
            $this->food_category = $food_category;
            $this->food_type = $food_type;
            return true;
        } else {
            return false;
        }
    }


    //Metod för att lägga till kurs
    public function addFood($food_name, $food_description, $food_price, $food_category, $food_type)
    {


        //Kontrollerar om set-metoden är uppfylld
        if (!$this->setFood($food_name, $food_description, $food_price, $food_category, $food_type)) return false;


        //Använder real_escape_string för att undvika att skadlig kod hamnar i databasen
        $food_name = $this->db->real_escape_string($food_name);
        $food_description = $this->db->real_escape_string($food_description);
        $food_price = $this->db->real_escape_string($food_price);
        $food_category = $this->db->real_escape_string($food_category);
        $food_type = $this->db->real_escape_string($food_type);

        //Använder strip_tags för att ta bort HTML-taggar
        $food_name = strip_tags($food_name);
        $food_description = strip_tags($food_description);
        $food_price = strip_tags($food_price);
        $food_category = strip_tags($food_category);
        $food_type = strip_tags($food_type);

        $sql = "INSERT INTO food(food_name, food_description, food_price, food_category_id, food_type_id) VALUES('$food_name', '$food_description', '$food_price', '$food_category', '$food_type')";

        $result = $this->db->query($sql);

        return $result;
    }


    //Uppdatera kurs
    public function updateFood($food_id, $food_name, $food_description, $food_price, $food_category, $food_type): bool
    {
        //Kontrollerar om set-metoden är uppfylld
        if (!$this->setFood($food_name, $food_description, $food_price, $food_category, $food_type)) return false;


        //Använder real_escape_string för att undvika att skadlig kod hamnar i databasen
        $food_name = $this->db->real_escape_string($food_name);
        $food_description = $this->db->real_escape_string($food_description);
        $food_price = $this->db->real_escape_string($food_price);
        $food_category = $this->db->real_escape_string($food_category);
        $food_type = $this->db->real_escape_string($food_type);

        //Använder strip_tags för att ta bort HTML-taggar
        $food_name = strip_tags($food_name);
        $food_description = strip_tags($food_description);
        $food_price = strip_tags($food_price);
        $food_category = strip_tags($food_category);
        $food_type = strip_tags($food_type);


        //SQL Fråga
        $sql = "UPDATE food SET food_name='$food_name', food_description='$food_description', food_price='$food_price', food_category_id='$food_category', food_type_id='$food_type' WHERE food_id=$food_id";
        //Skicka fråga
        return mysqli_query($this->db, $sql);
    }



    //Metod för att hämta lagrade bokningar
    public function getFood(): array
    {

        $sql = "SELECT food.*, food_category.food_category_name
        FROM food 
            LEFT JOIN food_category ON food.food_category_id = food_category.food_category_id";

        $result = mysqli_query($this->db, $sql);

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }


    //Ta bort bokning
    public function deleteFood(int $food_id): bool
    {
        $id = intval($food_id);

        //SQL fråga
        $sql = "DELETE from food WHERE food_id=$food_id";

        //Skicka frågan
        return mysqli_query($this->db, $sql);
    }


    //Metod för att hämta en specifik kurs m.h.a dess id
    public function getfoodById(int $food_id): array
    {

        $sql = "SELECT * FROM food WHERE food_id=$food_id;";
        $result = mysqli_query($this->db, $sql);

        return $result->fetch_assoc();
    }

    //Metod för att hämta viss maträtt beroende på vilken typ(förrätt/varmrätt/efterrätt/snack)
    public function getfoodByCategory(int $food_category): array
    {

        $sql = "SELECT * FROM food WHERE food_category_id=$food_category;";
        $result = mysqli_query($this->db, $sql);

        return $result->fetch_assoc();
    }

    //Destruktor
    function __destruct()
    {
        mysqli_close($this->db);
    }
}
