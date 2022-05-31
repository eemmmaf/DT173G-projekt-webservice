<?php
class User
{
    private $username;
    private $password;
    private $db;
    private $token;

    //Konstruktor med databasanslutning
    function __construct()
    {
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);

        //Kontrollerar om det finns något fel
        if ($this->db->connect_errno > 0) {
            die("Fel vid anslutning" . $this->db->connect_error);
        }
    }

    public function setToken(string $token)
    {
        if ($token != null || "") {
            $this->token = $token;
            return true;
        } else {
            return false;
        }
    }

    //Funktion för att generera en token vid inloggning
    public function createToken(string $username): string
    {
        $token = "";
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < 32; $i++) {
            $generating = mt_rand(0, $max);
            $token .= $characters[$generating];
        }
        return $token;

        $sql = "INSERT INTO tokens(token, usersname) VALUES('$token', '$username')";
        return mysqli_query($this->db, $sql);
    }



    //Funktion för att se om token finns i databasen
    public function validateToken(string $token)
    {

        //SQL-fråga
        $sql = "SELECT * FROM tokens WHERE token='$token'";

        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $token = $row['token'];
            true;
        } else {
            return false;
        }
    }

    //Metod för att ta bort token när användaren loggar ut
    public function deleteToken($username)
    {

        $sql = "UPDATE tokens set token=NULL WHERE usersname='$username'";


        $result = $this->db->query($sql);

        return $result;
    }


    //Setmetod för att kolla så att inte fältet är tomt
    public function setUser(string $username, string $password): bool
    {
        if ($username && $password != "") {
            $this->username = $username;
            $this->password = $password;

            $username = $this->db->real_escape_string($username);
            $password = $this->db->real_escape_string($password);

            $username = strip_tags($username);
            $password = strip_tags($password);
            return true;
        } else {
            return false;
        }
    }

    //Metod för att logga in 
    public function logIn(string $username, string $password): bool
    {

        //Kontrollerar om set-metoder är uppfyllda
        if (!$this->setUser($username, $password)) return false;


        //SQL-fråga
        $sql = "SELECT * FROM user WHERE username='$username'";
        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stored_password = $row['password'];

            //Kontrollerar det inmatade lösenordet mot det lagrade lösenordet
            if ($password == $stored_password) {
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
