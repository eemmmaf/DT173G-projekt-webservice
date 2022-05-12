<?php
//Autoload för klasser
spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.class.php'; 
});

//Variabel för inställning av databasanslutnings-uppgifter
$developer = true;
if($developer){
//Databasanslutning lokal server
define("DBHOST", "localhost");
define("DBUSER", "courseapi");
define("DBPASS", "Password");
define("DBDATABASE", "courseapi");
// Aktiverar Felmeddelanden 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

}else{
//Databasanslutning för publicerad webbplats
define("DBHOST", 'studentmysql.miun.se');
define("DBUSER", 'emfo2102');
define("DBPASS", 'X8jyGSt@dW');
define("DBDATABASE", 'emfo2102');
}
?>

?>