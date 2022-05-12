<?php

class Booking
{
    //Properties
    private $db; //Databas-anslutning
    private $booking_id; //Boknings-id
    private $booking_date;
    private $guest_fname;
    private $guest_ename;
    private $guest_email;


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
    public function setBooking(string $booking_date, string $guest_fname, string $guest_ename, string $guest_email): bool
    {
        if ($booking_date && $guest_fname && $guest_ename && $guest_email != "") {
            $this->booking_date = $booking_date;
            $this->guest_fname = $guest_fname;
            $this->guest_ename = $guest_ename;
            $this->guest_email = $guest_email;
            return true;
        } else {
            return false;
        }
    }


    //Metod för att lägga till kurs
    public function addBooking($booking_date, $guest_fname, $guest_ename, $guest_email)
    {


        //Kontrollerar om set-metoden är uppfylld
        if (!$this->setBooking($booking_date, $guest_fname, $guest_ename, $guest_email)) return false;


        //Använder real_escape_string för att undvika att skadlig kod hamnar i databasen
        $booking_date = $this->db->real_escape_string($booking_date);
        $guest_fname = $this->db->real_escape_string($guest_fname);
        $guest_ename = $this->db->real_escape_string($guest_ename);
        $guest_email = $this->db->real_escape_string($guest_email);

        //Använder strip_tags för att ta bort HTML-taggar
        $booking_date = strip_tags($booking_date);
        $guest_fname = strip_tags($guest_fname);
        $guest_ename = strip_tags($guest_ename);
        $guest_email = strip_tags($guest_email);

        $sql = "INSERT INTO booking(booking_date, guest_fname, guest_ename, guest_email) VALUES('$booking_date', '$guest_fname', '$guest_ename', '$guest_email')";

        $result = $this->db->query($sql);

        return $result;
    }


    //Uppdatera kurs
    public function updateCourse($booking_date, $guest_fname, $guest_ename, $guest_email, $booking_id): bool
    {
        //Kontrollerar om set-metoden är uppfylld
        if (!$this->setBooking($booking_date, $guest_fname, $guest_ename, $guest_email)) return false;


        //Använder real_escape_string för att undvika att skadlig kod hamnar i databasen
        $booking_date = $this->db->real_escape_string($booking_date);
        $guest_fname = $this->db->real_escape_string($guest_fname);
        $guest_ename = $this->db->real_escape_string($guest_ename);
        $guest_email = $this->db->real_escape_string($guest_email);

        //Använder strip_tags för att ta bort HTML-taggar
        $booking_date = strip_tags($booking_date);
        $guest_fname = strip_tags($guest_fname);
        $guest_ename = strip_tags($guest_ename);
        $guest_email = strip_tags($guest_email);


        //SQL Fråga
        $sql = "UPDATE course SET booking_date='$booking_date', guest_fname='$guest_fname', guest_ename='$guest_ename', guest_email='$guest_email' WHERE booking_id=$booking_id";
        //Skicka fråga
        return mysqli_query($this->db, $sql);
    }



    //Metod för att hämta lagrade bokningar
    public function getBookings(): array
    {

        $sql = "SELECT * FROM booking;";

        $result = mysqli_query($this->db, $sql);

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    //Ta bort bokning
    public function deleteBooking(int $booking_id): bool
    {
        $id = intval($booking_id);

        //SQL fråga
        $sql = "DELETE from course WHERE booking_id=$booking_id";

        //Skicka frågan
        return mysqli_query($this->db, $sql);
    }


    //Metod för att hämta en specifik kurs m.h.a dess id
    public function getCourseById(int $booking_id): array
    {

        $sql = "SELECT * FROM course WHERE id=$booking_id;";
        $result = mysqli_query($this->db, $sql);

        return $result->fetch_assoc();
    }


    //Destruktor
    function __destruct()
    {
        mysqli_close($this->db);
    }
}
