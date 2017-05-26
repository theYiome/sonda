<?php
session_start();
if(isset($_SESSION['logged_as'])){
    header('Location: admin.php'); 
    exit();
}
?>

<html>

<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Kalam&subset=latin-ext" rel="stylesheet">
    <link rel="stylesheet" href="styles/global.css" type="text/css">
    <link rel="stylesheet" href="styles/questionnaire.css" type="text/css">
    <link rel="stylesheet" href="default.css" type="text/css">
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
</head>

<body>
    <div class="main_block" id="top">
        <div id="title_text">
            <img src="imgs/pytajnik.png" width="100" heigth="100" style="float: left;"> 
            Strona ankietowa 1# LO w Jaśle
            <img src="imgs/pytajnik.png" width="100" heigth="100" style="float: right;">     
        </div>
    </div>

    </div>
    <div id="wrapper">
        <?php
        session_start();
        echo '<div class="server_msg">';
        if(isset($_SESSION['main_msg'])){
            echo '<div>';
            echo $_SESSION['main_msg'];
            echo '</div>';
            unset($_SESSION['main_msg']);
        }
        require_once('functions.php');
        require_once('../private/database_connect.php');
        if(@fsockopen($_SERVER['REMOTE_ADDR'], 80, $errstr, $errno, 1)){
            $proxy = true; 
            echo '<div>Proxy wykryte, nie możesz głosować :(</div>';
        } else $proxy = false;
        echo '</div>';

        $ip = get_ip();
        $query = 'Select * From QUESTIONNAIRES'; 
        $question_response = mysqli_query($DATABASE_CONNECT_INFO, $query); 
        if($question_response){
            while($question_row = mysqli_fetch_assoc($question_response))
            {   
                switch($question_row['state']){
                    case 1:
                        $query = "Select * From " . $question_row['votersT'] ." Where IPa='$ip'";
                        $IPresponse = mysqli_query($DATABASE_CONNECT_INFO, $query);
                        if(mysqli_num_rows($IPresponse) < 1) $votable = true;
                        else $votable = false;

                        echo '<div class="questionnaire">';
                        echo '<div class="questionnaire_title"><button class="slide_button" onclick="hideshow(this)"><div>+</div></button>' . $question_row['title'] . '</div>';
                        echo '<div class="questionnaire_info">' . $question_row['info'] . '</div>';
                        echo '<div class="questions_box">';
                        $query = "Select * From " . $question_row['answersT'];
                        $answers_response = mysqli_query($DATABASE_CONNECT_INFO, $query);
                        echo '<form action="php_vote.php" method="post">';
                        echo '<input type="number" name="qID" value="' . $question_row['ID'] . '">';
                        $iterator = 1;
                        while($answer_row = mysqli_fetch_assoc($answers_response))
                        {
                            if($votable) echo '<label><input type="radio" name="aID" value="'. $iterator . '"><div class="question">' . $answer_row['content'] . '</div></label>';
                            else{
                                echo '<div class="question">' . $answer_row['content'];
                                echo '<div class="votes"> Głosów : ' . $answer_row['votes'] . '</div>';
                                echo '</div>';
                            }
                            $iterator++;
                        }
                        if($votable){
                            if(!$proxy) echo '<input type="submit" class="hvr-wobble-vertical" value="Zagłosuj">';
                        }
                        else echo '<div class="info_box"> Zagłosowano </div>';
                        echo '</form> </div> </div>';
                    break;

                    case 2:
                        $query = "Select * From " . $question_row['votersT'] ." Where IPa='$ip'";
                        $IPresponse = mysqli_query($DATABASE_CONNECT_INFO, $query);
                        if(mysqli_num_rows($IPresponse) < 1) $votable = true;
                        else $votable = false;

                        echo '<div class="questionnaire">';
                        echo '<div class="questionnaire_title"><button class="slide_button" onclick="hideshow(this)"><div>+</div></button>' . $question_row['title'] . '</div>';
                        echo '<div class="questionnaire_info">' . $question_row['info'] . '</div>';
                        echo '<div class="questions_box">';
                        $query = "Select * From " . $question_row['answersT'];
                        $answers_response = mysqli_query($DATABASE_CONNECT_INFO, $query);
                        echo '<form action="php_vote.php" method="post">';
                        echo '<input type="number" name="qID" value="' . $question_row['ID'] . '">';
                        $iterator = 1;
                        while($answer_row = mysqli_fetch_assoc($answers_response))
                        {
                            if($votable) echo '<label><input type="radio" name="aID" value="'. $iterator . '"><div class="question">' . $answer_row['content'] . '</div></label>';
                            else echo '<div class="question">' . $answer_row['content'] . '</div>';
                            $iterator++;
                        }
                        if($votable){
                            if(!$proxy) echo '<input type="submit" class="hvr-wobble-vertical" value="Zagłosuj">';
                        }
                        else echo '<div class="info_box"> Zagłosowano </div>';
                        echo '</form> </div> </div>';
                    break;                  
                    
                    case 3:
                        echo '<div class="questionnaire">';
                        echo '<div class="questionnaire_title"><button class="slide_button" onclick="hideshow(this)"><div>+</div></button>' . $question_row['title'] . '</div>';
                        echo '<div class="questionnaire_info">' . $question_row['info'] . '</div>';
                        echo '<div class="questions_box">';
                        $query = "Select * From " . $question_row['answersT'];
                        $answers_response = mysqli_query($DATABASE_CONNECT_INFO, $query);
                        echo '<form action="php_vote.php" method="post">';
                        echo '<input type="number" name="qID" value="' . $question_row['ID'] . '">';
                        $iterator = 1;
                        while($answer_row = mysqli_fetch_assoc($answers_response))
                        {
                            if($votable) echo '<label><input type="radio" name="aID" value="'. $iterator . '"><div class="question">' . $answer_row['content'] . '</div></label>';
                            else{
                                echo '<div class="question">' . $answer_row['content'];
                                echo '<div class="votes"> Głosów : ' . $answer_row['votes'] . '</div>';
                                echo '</div>';
                            }
                            $iterator++;
                        }
                        echo '<div class="info_box"> Głosowanie Zablokowane </div>';
                        echo '</form> </div> </div>';
                    break;

                    case 4:

                    break;

                    default:
                        echo '<div class="info_box"> Błąd ' . $question_row['state'] . '</div>';
                    break;
                }
            }
            mysqli_close($DATABASE_CONNECT_INFO);
        } 
        ?>
        </div>
        <div class="main_block" id="bot">
            <a href="http://www.1lojaslo.pl/index.php/news" target="_blank"><img class="link" title="Strona szkoły" src="imgs/logo.png" width="118" heigth="100"></a>
            <a href="http://strefa38.ugu.pl/index.htm">                     <img class="link" title="Strona kółka szkolnego" src="imgs/s38.jpg" width="110" height="80" style="margin-top: 12px; border-radius: 5px"></a>
            <a href="http://yiome.16mb.com/">                               <img class="link" title="Strona twórcy" src="imgs/yiome.png" width="100" height="100" style="border-radius: 50px;"></a> 
            <a href="login_page.php">                                       <img class="hvr-wobble-vertical" title="Panel Administratora" src="imgs/config256.png" width="100" height="100" style="float: right;"></a> 
        </div>
    </body>
</html>