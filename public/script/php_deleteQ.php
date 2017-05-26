<?php
session_start();
if(!isset($_SESSION['logged_as'])){
    //WIADOMOŚĆ
    $_SESSION['login_msg'] = 'Twoja sesja wygasła, zaloguj się ponownie';
    header('Location: login_page.php'); 
    exit();
}

require_once('../private/database_connect.php');
require_once('functions.php');
if(isset($_POST['qID'])){ 
    $qID = to_query($_POST['qID'], $DATABASE_CONNECT_INFO);
    $query = "Select * From QUESTIONNAIRES Where ID='$qID'";
    $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
    //Sprawdz czy istnieje taka ankieta
    if(mysqli_num_rows($response) === 1){
        $data = mysqli_fetch_assoc($response);
        $query = "DELETE FROM QUESTIONNAIRES WHERE ID = $qID";
        $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
        //Sprawdz czy wpis ankiety został usunięty
        if($response){
            $delete_ANSWERS_query = "DROP TABLE " . $data['answersT'];
            $response = mysqli_query($DATABASE_CONNECT_INFO, $delete_ANSWERS_query);
            //Sprawdz czy tabela z odpowiedziami została usunięta
            if($response){
                $delete_VOTERS_query = "DROP TABLE " . $data['votersT'];
                $response = mysqli_query($DATABASE_CONNECT_INFO, $delete_VOTERS_query);
                //Sprawdz czy tabela z IP głosujących została usunięta
                if($response){
                    //WIADOMOŚĆ
                    $_SESSION['main_msg'] = 'Pomyślnie usunięto ankietę o ID = ' . $qID . '!';
                    mysqli_close($DATABASE_CONNECT_INFO); 
                    header('Location: admin.php'); 
                    exit();
                }
            }
        }
    }
}
//WIADOMOŚĆ
$_SESSION['main_msg'] = 'Wystąpił nieoczekiwany błąd!' . mysqli_error($DATABASE_CONNECT_INFO);
mysqli_close($DATABASE_CONNECT_INFO);
header('Location: admin.php'); 
?>