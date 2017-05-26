<?php
session_start();
if(!fsockopen($_SERVER['REMOTE_ADDR'], 80, $errstr, $errno, 1)){
    require_once('../private/database_connect.php');
    require_once('functions.php');
    $ip = get_ip();
    //Czy wartości przesłały się poprawnie? TRUE
    if(isset($_POST['qID']) and isset($_POST['aID'])){
        $qID = to_query($_POST['qID'], $DATABASE_CONNECT_INFO);
        $aID = to_query($_POST['aID'], $DATABASE_CONNECT_INFO);
        $query = "Select * From QUESTIONNAIRES Where ID=$qID";
        $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
        $data = mysqli_fetch_assoc($response);
        //Czy w bazie danych istnieje taka ankieta? TRUE
        if(mysqli_num_rows($response) === 1){
            $query = "Select * From " . $data['votersT'] ." Where IPa='$ip'";
            $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
            //Czy w tabeli zawierającej IP istnieje taki głosujący? FALSE
            if(mysqli_num_rows($response) < 1){
                $query = "INSERT INTO " . $data['votersT'] . " VALUES( NULL, '$ip')";
                $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
                //Czy do tabeli zawierającej IP poprawnie dodano nowe IP? TRUE
                if($response){ 
                    $query = "UPDATE " . $data['answersT'] . " SET votes = votes + 1 WHERE ID=$aID";
                    $response = mysqli_query($DATABASE_CONNECT_INFO, $query);
                    //Czy poprawnie zinkrementowano liczbę głosów? TRUE
                    if($response){
                        //WIADOMOŚĆ 
                        $_SESSION['main_msg'] = "Głos zaliczony!"; 
                        mysqli_close($DATABASE_CONNECT_INFO); 
                        header('Location: default.php');
                        exit();
                    }
                }
            }
        }
    }
}
//WIADOMOŚĆ 
$_SESSION['main_msg'] = 'Wystąpił nieoczekiwany błąd!<br>' . mysqli_error($DATABASE_CONNECT_INFO); 
mysqli_close($DATABASE_CONNECT_INFO); 
header('Location: default.php');
?>