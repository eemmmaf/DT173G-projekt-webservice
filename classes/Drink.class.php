<?php

class Booking
{
    //Properties
    private $db; //Databas-anslutning
    private $drink_id;
    private $drink_name;
    private $drink_description;
    private $drink_price;

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
    public function setBooking(string $drink_name, string $drink_description, int $drink_price): bool
    {
        if ($drink_name && $drink_description && $drink_price != "") {
            $this->drink_name = $drink_name;
            $this->drink_description = $drink_description;
            $this->drink_price = $drink_price;
            return true;
        } else {
            return false;
        }
    }


    //Metod för att lägga till kurs
    public function addBooking($drink_name, $drink_description, $drink_price)
    {


        //Kontrollerar om set-metoden är uppfylld
        if (!$this->setBooking($drink_name, $drink_description, $drink_price)) return false;


        //Använder real_escape_string för att undvika att skadlig kod hamnar i databasen
        $drink_name = $this->db->real_escape_string($drink_name);
        $drink_description = $this->db->real_escape_string($drink_description);
        $drink_price = $this->db->real_escape_string($drink_price);

        //Använder strip_tags för att ta bort HTML-taggar
        $drink_name = strip_tags($drink_name);
        $drink_description = strip_tags($drink_description);
        $drink_price = strip_tags($drink_price);

        $sql = "INSERT INTO booking(drink_name, drink_description, drink_price) VALUES('$drink_name', '$drink_description', '$drink_price')";

        $result = $this->db->query($sql);

        return $result;
    }


    //Uppdatera kurs
    public function updateCourse($drink_name, $drink_description, $drink_price, $drink_id): bool
    {
        //Kontrollerar om set-metoden är uppfylld
        if (!$this->setBooking($drink_name, $drink_description, $drink_price)) return false;


        //Använder real_escape_string för att undvika att skadlig kod hamnar i databasen
        $drink_name = $this->db->real_escape_string($drink_name);
        $drink_description = $this->db->real_escape_string($drink_description);
        $drink_price = $this->db->real_escape_string($drink_price);

        //Använder strip_tags för att ta bort HTML-taggar
        $drink_name = strip_tags($drink_name);
        $drink_description = strip_tags($drink_description);
        $drink_price = strip_tags($drink_price);


        //SQL Fråga
        $sql = "UPDATE course SET drink_name='$drink_name', drink_description='$drink_description', drink_price='$drink_price' WHERE drink_id=$drink_id";
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
        $id = intval($drink_id);

        //SQL fråga
        $sql = "DELETE from course WHERE drink_id=$drink_id";

        //Skicka frågan
        return mysqli_query($this->db, $sql);
    }


    //Metod för att hämta en specifik kurs m.h.a dess id
    public function getDrinkById(int $drink_id): array
    {

        $sql = "SELECT * FROM drink WHERE id=$drink_id;";
        $result = mysqli_query($this->db, $sql);

        return $result->fetch_assoc();
    }


    //Destruktor
    function __destruct()
    {
        mysqli_close($this->db);
    }
}
