<?php

class Booking
{
    //Properties
    private $db; //Databas-anslutning
    private $booking_id; //Boknings-id
    private $booking_time;
    private $booking_date;
    private $guest_fname;
    private $guest_ename;
    private $guest_email;
    private $guest_text;
    private $quantity;


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
    public function setBooking(string $booking_date, string $booking_time, string $guest_fname, string $guest_ename, string $guest_email, string $guest_text, int $quantity): bool
    {
        if ($booking_date && $booking_time && $guest_fname && $guest_ename && $guest_email && $quantity != "") {
            $this->booking_date = $booking_date;
            $this->booking_time = $booking_time;
            $this->guest_fname = $guest_fname;
            $this->guest_ename = $guest_ename;
            $this->guest_email = $guest_email;
            $this->quantity = $quantity;
            $this->guest_text = $guest_text;
            return true;
        } else {
            return false;
        }
    }


    //Metod för att lägga till boknin
    public function addBooking($booking_date, $booking_time, $guest_fname, $guest_ename, $guest_email, $guest_text, $quantity)
    {


        //Kontrollerar om set-metoden är uppfylld
        if (!$this->setBooking($booking_date, $booking_time, $guest_fname, $guest_ename, $guest_email, $guest_text, $quantity)) return false;

        //Använder real_escape_string för att undvika att skadlig kod hamnar i databasen
        $booking_date = $this->db->real_escape_string($booking_date);
        $booking_time = $this->db->real_escape_string($booking_time);
        $guest_fname = $this->db->real_escape_string($guest_fname);
        $guest_ename = $this->db->real_escape_string($guest_ename);
        $guest_email = $this->db->real_escape_string($guest_email);
        $guest_text = $this->db->real_escape_string($guest_text);
        $quantity = $this->db->real_escape_string($quantity);

        //Använder strip_tags för att ta bort HTML-taggar
        $booking_date = strip_tags($booking_date);
        $booking_time = strip_tags($booking_time);
        $guest_fname = strip_tags($guest_fname);
        $guest_ename = strip_tags($guest_ename);
        $guest_email = strip_tags($guest_email);
        $guest_text = strip_tags($guest_text);
        $quantity = strip_tags($quantity);


        $sql = "INSERT INTO booking(booking_date, booking_time, guest_fname, guest_ename, guest_email, guest_text, quantity) VALUES('$booking_date', '$booking_time', '$guest_fname', '$guest_ename', '$guest_email', '$guest_text', '$quantity')";

        $result = $this->db->query($sql);

        return $result;
    }


    //Uppdatera bokning
    public function updateBooking($booking_id, $booking_date, $booking_time, $guest_fname, $guest_ename, $guest_email, $guest_text, $quantity): bool
    {
        //Kontrollerar om set-metoden är uppfylld
        if (!$this->setBooking($booking_date, $booking_time, $guest_fname, $guest_ename, $guest_email, $guest_text, $quantity)) return false;


        //Använder real_escape_string för att undvika att skadlig kod hamnar i databasen
        $booking_date = $this->db->real_escape_string($booking_date);
        $booking_time = $this->db->real_escape_string($booking_time);
        $guest_fname = $this->db->real_escape_string($guest_fname);
        $guest_ename = $this->db->real_escape_string($guest_ename);
        $guest_email = $this->db->real_escape_string($guest_email);
        $guest_text = $this->db->real_escape_string($guest_text);
        $quantity = $this->db->real_escape_string($quantity);

        //Använder strip_tags för att ta bort HTML-taggar
        $booking_date = strip_tags($booking_date);
        $booking_time = strip_tags($booking_time);
        $guest_fname = strip_tags($guest_fname);
        $guest_ename = strip_tags($guest_ename);
        $guest_email = strip_tags($guest_email);
        $guest_text = strip_tags($guest_text);
        $quantity = strip_tags($quantity);


        //SQL Fråga
        $sql = "UPDATE booking SET booking_date='$booking_date', booking_time='$booking_time', guest_fname='$guest_fname', guest_ename='$guest_ename', guest_email='$guest_email', guest_text='$guest_text', quantity='$quantity' WHERE booking_id=$booking_id";
        //Skicka fråga
        return mysqli_query($this->db, $sql);
    }



    //Metod för att hämta lagrade bokningar och ordnar dom efter den som skapades nyligast
    public function getBookings(): array
    {

        $sql = "SELECT * FROM booking ORDER BY created DESC;";

        $result = mysqli_query($this->db, $sql);

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    //Ta bort bokning
    public function deleteBooking(int $booking_id): bool
    {
        $id = intval($booking_id);

        //SQL fråga
        $sql = "DELETE from booking WHERE booking_id=$booking_id";

        //Skicka frågan
        return mysqli_query($this->db, $sql);
    }


    //Metod för att hämta en specifik bokning m.h.a dess id
    public function getBookingById(int $booking_id): array
    {

        $sql = "SELECT * FROM booking WHERE booking_id=$booking_id;";
        $result = mysqli_query($this->db, $sql);

        return $result->fetch_assoc();
    }


    //Destruktor
    function __destruct()
    {
        mysqli_close($this->db);
    }
}
