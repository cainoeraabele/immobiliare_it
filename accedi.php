<?php
/**
 * Created by PhpStorm.
 * User: lucabuonomo1
 * Date: 08/09/15
 * Time: 23:51
 */
include 'db_connection.php';
include 'functions.php';
priv_session_start(); // usiamo la funzione per la sezione.
if(isset($_POST['email'], $_POST['p'])) {
    $email = $_POST['email'];
    $password = $_POST['p']; // Recupero la password criptata.
    if(login($email, $password, $mysqli) == true) {
        // Login eseguito con successo
        echo 'Success: You have been logged in!</br>';
        ?>
        <a href="logout.php"><strong>LogOut</strong></a>
        <a href="login.php"><strong>Home</strong></a>
    <?
    } else {
        // Login fallito passa il errore 1
        header('Location: ./login.php?error=1');
    }
} else {
    echo 'Invalid Request';
}