<?php
session_set_cookie_params([
    'lifetime' => 0,            
    'path' => '/',
    'domain' => '',             
    'secure' => true,           
    'httponly' => true,         
    'samesite' => 'Strict'      
]);

session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: ../view/login.php');
    exit;
}
?>