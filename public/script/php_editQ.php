<?php
session_start();
if(!isset($_SESSION['logged_as'])){
    //WIADOMOŚĆ
    $_SESSION['login_msg'] = 'Twoja sesja wygasła, zaloguj się ponownie';
    header('Location: login_page.php');
    exit();
}
/* 
Służy do edycji istniejącej ankiety, pobiera następujące dane metodą POST:
ID, title, info, state,
answer0 , answer1, answer2, answer3, ...
*/
function end_msg($msg, $DB_C){
    //WIADOMOŚĆ
    $_SESSION['main_msg'] = $msg . mysqli_error($DB_C);
    mysqli_close($DB_C);
    header('Location: admin.php'); 
    exit();
}

if(isset($_POST['ID']) and isset($_POST['title']) and isset($_POST['info']) and isset($_POST['state'])){
    require_once('../private/database_connect.php');
    require_once('functions.php');
    $ID           = to_query($_POST['ID'], $DATABASE_CONNECT_INFO);
    $NEW_title    = to_query($_POST['title'], $DATABASE_CONNECT_INFO);
    $NEW_info     = to_query($_POST['info'], $DATABASE_CONNECT_INFO);
    $NEW_state    = to_query($_POST['state'], $DATABASE_CONNECT_INFO);
    // Pobierz dane o ankiecie
    $query = "SELECT * FROM QUESTIONNAIRES WHERE ID=$ID";
    $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
    if($response){
        $answersT = mysqli_fetch_assoc($response)['answersT'];
        $query = "UPDATE QUESTIONNAIRES SET title='$NEW_title', info='$NEW_info', state='$NEW_state' WHERE ID=$ID";
        $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
        //Zapisz nowe odpowiedzi 
        if($response){             
            for($i = 0; isset($_POST['answer' . $i]); $i++){
                $content = to_query($_POST['answer' . $i], $DATABASE_CONNECT_INFO);
                // Sprawdz czy istneje taka odpowiedz
                $query = "SELECT * FROM $answersT WHERE ID=" . ($i + 1);
                $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
                if(mysqli_num_rows($response) === 1){
                    $query = "UPDATE $answersT SET content='$content' WHERE ID=" . ($i + 1);
                    $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
                    // Jeżeli błąd to zakończ
                    //WIADOMOŚĆ
                    if(!$response) end_msg("Błąd: ", $DATABASE_CONNECT_INFO);
                }
                else{
                    $query = "INSERT INTO $answersT VALUES(NULL, '$content', '0')";
                    $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
                    // Jeżeli błąd to zakończ
                    //WIADOMOŚĆ
                    if(!$response) end_msg("Błąd: ", $DATABASE_CONNECT_INFO);
                }
            }
            /*$query = "DELETE FROM $answersT WHERE ID>=$i";
            $response = mysqli_query($DATABASE_CONNECT_INFO, $query);*/
            $_SESSION['login_msg'] = 'Pomyślnie dokonano edycji!';
            mysqli_close($DATABASE_CONNECT_INFO);
            header('Location: admin.php'); 
            exit();
        }
    }
}
//WIADOMOŚĆ
end_msg("Błąd: ", $DATABASE_CONNECT_INFO);
?>