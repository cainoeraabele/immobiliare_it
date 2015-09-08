<?php
/**
 * Created by PhpStorm.
 * User: lucabuonomo1
 * Date: 08/09/15
 * Time: 23:39
 */
function priv_session_start() {
    $session_name = 'priv_session_id';
    $secure = false;
    $httponly = true;
    ini_set('session.use_only_cookies', 1); // Forza la sessione ad utilizzare solo i cookie.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
    session_name($session_name);
    session_start();
    session_regenerate_id();
}


function login($email, $password,$mysqli ) {
    // prepared evita injection


    if ($stmt = $mysqli->prepare("SELECT id, idAgenzia,nomeTitolare,cognomeTitolare, password, salt FROM Tb_agenzia WHERE email = ? LIMIT 1")) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $id_azienda, $nome,$cognome, $db_password, $salt); // memorizza risultati nelle variabili.
        $stmt->fetch();

        $password = hash('sha512', $password);

        if($stmt->num_rows == 1) { // esiste
            //verifica attaco
            if(checkB($user_id, $mysqli) == true) {
                // Account disabilitato si può inviare una mail
                echo "account lock causa rischio compromissione";
                return false;
            } else {
                if($db_password == $password) { // controlla le pw
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id); //
                    $_SESSION['user_id'] = $user_id;
                    $idAzienda = preg_replace("/[^0-9]+/", "", $id_azienda);
                    $_SESSION['idAzienda'] = $idAzienda;
                    $nomeTitolare = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $nome);
                    $_SESSION['nomeTitolare'] = $nomeTitolare;
                    $cognomeTitolare = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $cognome);
                    $_SESSION['cognomeTitolare'] = $cognomeTitolare;
                    $_SESSION['login_string'] = hash('sha512', $password.$user_browser);
                    // Login corretto
                    return true;
                } else {
                    // Password incorretta.
                    // Registriamo il tentativo fallito nel database.
                    $now = time();// data
                    $mysqli->query("INSERT INTO login_attempts (user_id, time) VALUES ('$user_id', '$now')");
                    return false;
                }
            }
        } else {
            // L'utente inserito non esiste.
            return false;
        }
    }
}

function login_check($mysqli) {


    if(isset($_SESSION['user_id'], $_SESSION['idAzienda'], $_SESSION['login_string'])) {
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $idAzienda = $_SESSION['idAzienda'];
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
        if ($stmt = $mysqli->prepare("SELECT password FROM members WHERE id = ? LIMIT 1")) {
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows == 1) { // se l'utente esiste
                $stmt->bind_result($password); // recupera le variabili dal risultato ottenuto.
                $stmt->fetch();
                $login_check = hash('sha512', $password.$user_browser);
                if($login_check == $login_string) {
                    // Login eseguito!!!!
                    return true;
                } else {
                    //  Login non eseguito
                    return false;
                }
            } else {
                // Login non eseguito
                return false;
            }
        } else {
            // Login non eseguito
            return false;
        }
    } else {
        // Login non eseguito
        return false;
    }
}

function checkB($user_id, $mysqli) {
    // Recupero il timestamp
    $now = time();
    // Vengono analizzati tutti i tentativi di login a partire dalle ultime due ore.
    $valid_attempts = $now - (2 * 60 * 60);
    if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE user_id = ? AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);
        // Eseguo la query creata.
        $stmt->execute();
        $stmt->store_result();
        // Verifico l'esistenza di più di 5 tentativi di login falliti.
        if($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}
