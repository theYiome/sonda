<?php
session_start();
if(!isset($_SESSION['logged_as'])){
    //WIADOMOŚĆ
    $_SESSION['login_msg'] = 'Twoja sesja wygasła, zaloguj się ponownie';
    header('Location: login_page.php');
    exit();
}
?>
<?php
session_start();
if(!isset($_POST['qID'])){
    header('Location: admin.php');
    exit();
}
?>
<html>

<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Kalam&subset=latin-ext" rel="stylesheet">
    <link rel="stylesheet" href="styles/global.css" type="text/css">
    <link rel="stylesheet" href="styles/update.css" type="text/css">
    <link rel="stylesheet" href="edit_questionnaire.css" type="text/css">
    <script src="utility.js"></script>
</head>

<body>
    <div id="wrapper">
        <div id="questionnaire-block">
        <form action="php_editQ.php" method="post">
                <?php
                    require_once('../private/database_connect.php');
                    session_start();
                    $ID = $_POST['qID'];
                    $query = "Select * From QUESTIONNAIRES Where ID='$ID'";
                    $qData = mysqli_fetch_assoc(mysqli_query($DATABASE_CONNECT_INFO, $query));
                    echo '<input type="text" name="title" id="title" value="' . htmlentities($qData['title'], ENT_QUOTES, "UTF-8") .'">';
                    echo '<input type="text" name="info"  id="info"  value="' . htmlentities($qData['info'], ENT_QUOTES, "UTF-8") .'">';
                    $query = "Select * From " . $qData['answersT'];
                    $answers_response = mysqli_query($DATABASE_CONNECT_INFO, $query);
                    for($iterator = 0; $answer_row = mysqli_fetch_assoc($answers_response); $iterator++){
                        echo '<input type="text" name="answer' . $iterator . '" class="answer" value="' . htmlentities($answer_row['content'], ENT_QUOTES, "UTF-8") . '">';
                    }
                ?>
        </div>
        <div id="buttons-block">
            <a href="admin.php">
            <div id="return" onclick="dim()">
                <img src="imgs/arrow128.png">
                <div>Powrót</div>
            </div>
            </a>
            <?php
                require_once('../private/database_connect.php');
                session_start();
                $ID = $_POST['qID'];
                echo '<input type="number" name="ID" value="' . $ID . '">';
                $query = "Select * From QUESTIONNAIRES Where ID='$ID'";
                $qData = mysqli_fetch_assoc(mysqli_query($DATABASE_CONNECT_INFO, $query));
                switch($qData['state']){
                    case 1;
                        echo '<label><input type="radio" name="state" value="1" checked><div class="radio_box">Ankieta widoczna <br> Głosy widoczne <br> Można głosować</div></label>';
                        echo '<label><input type="radio" name="state" value="2"><div class="radio_box">Ankieta widoczna <br> Głosy niewidoczne <br> Można głosować</div></label>';
                        echo '<label><input type="radio" name="state" value="3"><div class="radio_box">Ankieta widoczna <br> Głosy widoczne <br> Nie można głosować</div></label>';
                        echo '<label><input type="radio" name="state" value="4"><div class="radio_box">Ankieta ukryta <br> Głosy niewidoczne <br> Nie można głosować</div></label>';
                    break;

                    case 2;
                        echo '<label><input type="radio" name="state" value="1"><div class="radio_box">Ankieta widoczna <br> Głosy widoczne <br> Można głosować</div></label>';
                        echo '<label><input type="radio" name="state" value="2" checked><div class="radio_box">Ankieta widoczna <br> Głosy niewidoczne <br> Można głosować</div></label>';
                        echo '<label><input type="radio" name="state" value="3"><div class="radio_box">Ankieta widoczna <br> Głosy widoczne <br> Nie można głosować</div></label>';
                        echo '<label><input type="radio" name="state" value="4"><div class="radio_box">Ankieta ukryta <br> Głosy niewidoczne <br> Nie można głosować</div></label>';
                    break;

                    case 3;
                        echo '<label><input type="radio" name="state" value="1"><div class="radio_box">Ankieta widoczna <br> Głosy widoczne <br> Można głosować</div></label>';
                        echo '<label><input type="radio" name="state" value="2"><div class="radio_box">Ankieta widoczna <br> Głosy niewidoczne <br> Można głosować</div></label>';
                        echo '<label><input type="radio" name="state" value="3" checked><div class="radio_box">Ankieta widoczna <br> Głosy widoczne <br> Nie można głosować</div></label>';
                        echo '<label><input type="radio" name="state" value="4"><div class="radio_box">Ankieta ukryta <br> Głosy niewidoczne <br> Nie można głosować</div></label>';
                    break;

                    case 4;
                        echo '<label><input type="radio" name="state" value="1"><div class="radio_box">Ankieta widoczna <br> Głosy widoczne <br> Można głosować</div></label>';
                        echo '<label><input type="radio" name="state" value="2"><div class="radio_box">Ankieta widoczna <br> Głosy niewidoczne <br> Można głosować</div></label>';
                        echo '<label><input type="radio" name="state" value="3"><div class="radio_box">Ankieta widoczna <br> Głosy widoczne <br> Nie można głosować</div></label>';
                        echo '<label><input type="radio" name="state" value="4" checked><div class="radio_box">Ankieta ukryta <br> Głosy niewidoczne <br> Nie można głosować</div></label>';
                    break;
                }
            ?>
            <input type="submit" id="done" value="Dokonaj Edycji">
        </form>

            <form action="php_deleteQ.php" method="post">
            <?php
                session_start();
                echo '<input type="number" name="qID" value="' . $_POST['qID'] . '">';
            ?>
            <label id="delete">
                <img src="imgs/delete.png"> 
                <div>Usuń Ankietę</div>
                <input type="submit" onclick="return confirm('Ankieta zostanie bezpowrotnie usunięta, tej akcji nie można cofnąć!')" style="display: none;">
            </label>
            </form>
        </div>
    </div>
</body>

</html>