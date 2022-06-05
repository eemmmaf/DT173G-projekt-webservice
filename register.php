<?php
include('config.php');
session_start();

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $register = new User();
    $success = true; //Variabel för när det postade är OK

    //Anropar setmetoder. Registrerar inte användaren om setMetoderna inte uppfylls
    if (!$register->setUser($username, $password)) {
        $success = false;
    }
    if ($register->registerUser($username, $password)) {
        $message = "<p> Användare skapad </p>";
    }
}
?>
    
    
    <!--Formulär-->
    <form action="register.php" method="POST" id="register">
        <!--Epost-->
        <label for="username">Användarnamn:</label><br>
        <input type="username" name="username" id="username"><br><br>
        <div class="error-js"></div>
        <!--Lösenord-->
        <label for="password">Lösenord:</label><br>
        <input type="password" name="password" id="password" placeholder="Lösenordet måste innehålla minst 8 tecken"><br><br>
        <p id="ok"></p>

        <!--Godkänn lagring-->
        <input type="checkbox" id="approve" name="approve" value="Jag godkänner"">
        <!--Logga in-->
        <input type="submit" value="Skapa blogg" id="submitEl">
