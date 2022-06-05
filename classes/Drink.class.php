<?php

class Drink
{
    //Properties
    private $db; //Databas-anslutning
    private $drink_id;
    private $drink_name;
    private $drink_description;
    private $drink_price;
    private $drink_category;

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
    public function setDrink(string $drink_name, string $drink_description, string $drink_price, int $drink_category): bool
    {
        if ($drink_name && $drink_description && $drink_price && $drink_category != "") {
            $this->drink_name = $drink_name;
            $this->drink_description = $drink_description;
            $this->drink_price = $drink_price;
            $this->drink_category = $drink_category;
            return true;
        } else {
            return false;
        }
    }


    //Metod för att lägga till kurs
    public function addDrink($drink_name, $drink_description, $drink_price, $drink_category)
    {


        //Kontrollerar om set-metoden är uppfylld
        if (!$this->setDrink($drink_name, $drink_description, $drink_price, $drink_category)) return false;


        //Använder real_escape_string för att undvika att skadlig kod hamnar i databasen
        $drink_name = $this->db->real_escape_string($drink_name);
        $drink_description = $this->db->real_escape_string($drink_description);
        $drink_price = $this->db->real_escape_string($drink_price);
        $drink_category = $this->db->real_escape_string($drink_category);

        //Använder strip_tags för att ta bort HTML-taggar
        $drink_name = strip_tags($drink_name);
        $drink_description = strip_tags($drink_description);
        $drink_price = strip_tags($drink_price);
        $drink_category = strip_tags($drink_category);

        $sql = "INSERT INTO drink(drink_name, drink_description, drink_price, drink_category_id) VALUES('$drink_name', '$drink_description', '$drink_price', '$drink_category')";

        $result = $this->db->query($sql);

        return $result;
    }


    //Uppdatera kurs
    public function updateDrink($drink_id, $drink_name, $drink_description, $drink_price, $drink_category): bool
    {
        //Kontrollerar om set-metoden är uppfylld
        if (!$this->setDrink($drink_name, $drink_description, $drink_price, $drink_category)) return false;

        //Använder real_escape_string för att undvika att skadlig kod hamnar i databasen
        $drink_name = $this->db->real_escape_string($drink_name);
        $drink_description = $this->db->real_escape_string($drink_description);
        $drink_price = $this->db->real_escape_string($drink_price);

        //Använder strip_tags för att ta bort HTML-taggar
        $drink_name = strip_tags($drink_name);
        $drink_description = strip_tags($drink_description);
        $drink_price = strip_tags($drink_price);


        //SQL Fråga
        $sql = "UPDATE drink SET drink_name='$drink_name', drink_description='$drink_description', drink_price='$drink_price' WHERE drink_id=$drink_id";
        //Skicka fråga
        return mysqli_query($this->db, $sql);
    }



    //Metod för att hämta lagrade bokningar
    public function getDrinks(): array
    {

        $sql = "SELECT * FROM drink;";

        $result = mysqli_query($this->db, $sql);

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }



    //Ta bort bokning
    public function deleteDrink(int $drink_id): bool
    {
        $drink_id = intval($drink_id);

        //SQL fråga
        $sql = "DELETE from drink WHERE drink_id=$drink_id";

        //Skicka frågan
        return mysqli_query($this->db, $sql);
    }


    //Metod för att hämta en specifik kurs m.h.a dess id
    public function getDrinkById(int $drink_id): array
    {

        $sql = "SELECT * FROM drink WHERE drink_id=$drink_id;";
        $result = mysqli_query($this->db, $sql);

        return $result->fetch_assoc();
    }

    public function getDrinkByCategory(int $drink_category): array
    {
        $sql = "SELECT * FROM drink WHERE drink_category_id=$drink_category;";
        $result = mysqli_query($this->db, $sql);

        return $result->fetch_assoc();
    }


    //Destruktor
    function __destruct()
    {
        mysqli_close($this->db);
    }
}
