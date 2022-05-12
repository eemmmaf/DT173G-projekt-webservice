<?php
include('config.php');
//Anslut
$db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
if ($db->connect_errno > 0) {
    die("Fel vid anslutning" . $db->connect_error);
}