<?php
session_start();
if(!isset($_SESSION['logged_as'])){
    //WIADOMOŚĆ
    $_SESSION['login_msg'] = 'Twoja sesja wygasła, zaloguj się ponownie';
    header('Location: login_page.php'); exit();
}
?>
<html>
    
<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Kalam&subset=latin-ext" rel="stylesheet">
    <link rel="stylesheet" href="styles/global.css" type="text/css">
    <link rel="stylesheet" href="styles/questionnaire.css" type="text/css">
    <link rel="stylesheet" href="admin.css" type="text/css">
    <script>
        function hideshow(element){
            const object = element.parentElement.parentElement.getElementsByClassName("questions_box")[0];
            if(object.style.display === "block"){
                object.style.display = "none";
                element.innerHTML = "<div>+</div>";
                element.style.backgroundColor = "mediumseagreen";
                element.style.color = "darkgreen";
            } else {
                object.style.display = "block";
                element.innerHTML = "<div>-</div>";
                element.style.backgroundColor = "indianred";
                element.style.color = "darkred";
            }
        }
    </script>
    <script src="utility.js"></script>
</head>

<body>

<div class="main_block" id="top">
    <div id="title_text">
        <a href="php_logout.php">
            <img src="imgs/arrow128.png" width="100" heigth="100" title="Wyloguj" onclick="dim()" style="display: block; float: left; width: 100px; background-color: rgba(16, 200, 68, 0.5); border-radius: 16px;">
        </a>
         Panel Administratora
        <img src="imgs/pytajnik.png" width="100" heigth="100" style="float: right;">     
    </div>
</div>
    <div id="wrapper">
        <?php
            if(isset($_SESSION['main_msg'])){
                echo '<div class="server_msg">' . $_SESSION['main_msg'] . '</div>';
                unset($_SESSION['main_msg']);
            } 
        ?>
        <a href="new_questionnaire.php">
            <div id="new_questionnaire" onclick="dim()">
                <img src="imgs/new.png" style="float: left; width: 100px; height: 100px;">NOWA ANKIETA
            </div>
        </a>
        <?php
            require_once('../private/database_connect.php');
            $query = 'Select * From QUESTIONNAIRES'; 
            $question_response = mysqli_query($DATABASE_CONNECT_INFO, $query); 
            if($question_response){
                while($question_row = mysqli_fetch_assoc($question_response)){   
                    echo '<div class="questionnaire">';
                    echo '<div class="questionnaire_title"><button class="slide_button" onclick="hideshow(this)"><div>+</div></button>' . $question_row['title'];
                    echo '</div>';
                    echo '<div class="questionnaire_info">' . $question_row['info'] . '</div>';
                    echo '<div class="questions_box">';
                    $query = "Select * From " . $question_row['answersT'];
                    $answers_response = mysqli_query($DATABASE_CONNECT_INFO, $query);
                    while($answer_row = mysqli_fetch_assoc($answers_response)){
                        echo '<div class="question">' . $answer_row['content'];
                        echo '<div class="votes"> Głosów : ' . $answer_row['votes'] . '</div>';
                        echo '</div>';
                    }
                    echo '<form action="edit_questionnaire.php" method="post">
                          <input type="number" name="qID" value="' . $question_row['ID'] .'">
                          <input type="submit" value="Edytuj">
                          </form>';
                    echo '</div>';
                    echo '</div>';
                }
                mysqli_close($DATABASE_CONNECT_INFO);
            } 
        ?>
    </div>
</body>

</html>
