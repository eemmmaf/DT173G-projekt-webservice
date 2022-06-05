<?php
class User
{
    private $username;
    private $password;
    private $db;

    //Konstruktor med databasanslutning
    function __construct()
    {
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);

        //Kontrollerar om det finns något fel
        if ($this->db->connect_errno > 0) {
            die("Fel vid anslutning" . $this->db->connect_error);
        }
    }


    //Setmetod för att kolla så att inte fältet är tomt
    public function setUser(string $username, string $password): bool
    {
        if ($username && $password != "") {
            $this->username = $username;
            $this->password = $password;

            return true;
        } else {
            return false;
        }
    }

    //Metod för att registrera användare
    public function registerUser($username, $password)
    {


        //Kontrollerar om set-metoder är uppfyllda
        if (!$this->setUser($username, $password));


        //Hashar lösenordet
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        //Använder real_escape_string för att undvika att skadlig kod hamnar i databasen
        $username = $this->db->real_escape_string($username);
        $password = $this->db->real_escape_string($password);

        //Använder strip_tags för att ta bort HTML-taggar
        $username = strip_tags($username);
        $password = strip_tags($password);


        //SQL-fråga
        $sql = "INSERT INTO user(username, password) VALUES('$username', '$hashed_password')";

        $result = $this->db->query($sql);

        return $result;
    }


    //Metod för att logga in 
    public function logIn(string $username, string $password): bool
    {

        //Kontrollerar om set-metoder är uppfyllda
        if (!$this->setUser($username, $password)) return false;

        $username = $this->db->real_escape_string($username);
        $password = $this->db->real_escape_string($password);

        $username = strip_tags($username);
        $password = strip_tags($password);


        //SQL-fråga
        $sql = "SELECT * FROM user WHERE username='$username'";
        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stored_password = $row['password'];

            //Kontrollerar det inmatade lösenordet mot det lagrade lösenordet
            if (password_verify($password, $stored_password)) {
                $_SESSION['admin'] = $username;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Destruktor
    function __destruct()
    {
        mysqli_close($this->db);
    }
}
