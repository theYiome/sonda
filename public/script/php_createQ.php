<?php
session_start();
if(!isset($_SESSION['logged_as'])){
    //WIADOMOŚĆ
    $_SESSION['login_msg'] = 'Twoja sesja wygasła, zaloguj się ponownie';
    header('Location: login_page.php');
    exit();
}

if(isset($_POST['title']) and isset($_POST['info']) and isset($_POST['state'])){
    require_once('../private/database_connect.php');
    require_once('functions.php');
    $title = $_POST['title'];
    //$title = to_query($title, $DATABASE_CONNECT_INFO);
    $info = $_POST['info'];
    //$info = to_query($info, $DATABASE_CONNECT_INFO);
    $state = $_POST['state'];
    //Wylosuj nazwę dla tabeli i sprawdz czy jest taka w bazie, wykonuj dopóki TRUE
    do{
        $voters = random_string(28);
        $query = "Select * From '$voters'";
        $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
    } while ($response);
    //Wylosuj nazwę dla tabeli i sprawdz czy jest taka w bazie, wykonuj dopóki TRUE
    do{
        $answers = random_string(28);
        $query = "Select * From '$answers'";
        $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
    } while ($response);
    // ID, title, info, votersT, answersT, state -> baza danych 
    $query = "INSERT INTO QUESTIONNAIRES VALUES(NULL, '$title', '$info', '$voters', '$answers', '$state')";
    $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
    //Czy poprawnie stworzono nowy wpis ankiety? TRUE
    if($response){
    // ID, IPa -> baza danych  
    $query = "CREATE TABLE $voters(ID INT UNSIGNED PRIMARY KEY AUTO_INCREMENT, IPa VARCHAR(48) NOT NULL)";
    $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
        //Czy poprawnie stworzono nową tabelę na IP głosujących? TRUE  
        if($response){
        // ID, content, votes = 0, -> baza danych
        $query = "CREATE TABLE $answers(ID INT UNSIGNED PRIMARY KEY AUTO_INCREMENT, content TEXT NOT NULL, votes INT UNSIGNED)";
        $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
        //Czy poprawnie stworzono nową tabelę na odpowiedzi? TRUE    
            if($response){
                for($i = 0; isset($_POST['answer' . $i]); $i++){
                    $content = $_POST['answer' . $i];
                    $content = to_query($content, $DATABASE_CONNECT_INFO);
                    $query = "INSERT INTO $answers VALUES(NULL, '$content', '0')";
                    $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
                    //Wstawiaj danę do tabeli dopóki wszystko OK
                    if(!$response){
                        //WIADOMOŚĆ
                        $_SESSION['main_msg'] = 'Błąd wprowadznia pytania do tabeli! ' . mysqli_error($DATABASE_CONNECT_INFO);
                        mysqli_close($DATABASE_CONNECT_INFO);
                        header('Location: admin.php'); 
                        exit();
                    }
                }
                //WIADOMOŚĆ
                $_SESSION['main_msg'] = 'Nowa ankieta utworzona pomyślnie!';
                mysqli_close($DATABASE_CONNECT_INFO);
                header('Location: admin.php'); 
                exit();
            } 
        } 
    } 
}
//WIADOMOŚĆ
$_SESSION['main_msg'] = 'Wystąpił nieoczekiwany błąd!' . mysqli_error($DATABASE_CONNECT_INFO);
mysqli_close($DATABASE_CONNECT_INFO);
header('Location: admin.php'); 
?>