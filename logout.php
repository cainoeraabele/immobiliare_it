<?php
/**
 * Created by PhpStorm.
 * User: lucabuonomo1
 * Date: 08/09/15
 * Time: 23:21
 */
include 'functions.php';
priv_session_start();
$_SESSION = array();
$params = session_get_cookie_params();
// Cancella i cookie
setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
// Cancella sessione
session_destroy();
header('Location: ./login.php');