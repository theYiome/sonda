<?php
session_start();
require_once('../private/database_connect.php');
require_once('functions.php');

$login = $_POST['login'];
$password = $_POST['password'];
$login = to_query($login, $DATABASE_CONNECT_INFO);
$password = to_query($password, $DATABASE_CONNECT_INFO);

$query = "Select * From ADMINS Where username='$login' And password='$password'";

$response = mysqli_query($DATABASE_CONNECT_INFO, $query);
if($response){
    if($row = mysqli_fetch_assoc($response))
    {
        $_SESSION['logged_as'] = $login;
        unset($_SESSION['login_msg']);
        header('Location: admin.php'); 
        exit();
    }
}
//WIADOMOŚĆ
$_SESSION['login_msg'] = 'Nieprawidłowe dane logowania';
header('Location: login_page.php'); 
?>