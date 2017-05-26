<?php
session_start();
if(!isset($_SESSION['logged_as']))
{
    $_SESSION['authorization_error'] = 'Twoja sesja wygasła, zaloguj się ponownie';
    header('Location: login_page.php');
}
?>
<html>

<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Kalam&subset=latin-ext" rel="stylesheet">
    <link rel="stylesheet" href="styles/global.css" type="text/css">
    <link rel="stylesheet" href="styles/update.css" type="text/css">
    <link rel="stylesheet" href="new_questionnaire.css" type="text/css">
    <script>
        let childCounter = 0;
        function appendInput(){
            const input = document.createElement("input");
            input.type  = "text";
            input.name  = "answer" + childCounter;
            input.className = "answer";
            input.placeholder = "Treść " + (childCounter + 1) + ". pytania";
            document.getElementById("questions").appendChild(input);
            childCounter++;
        }

        function deleteInput(){
            if(childCounter > 0){
                const parent = document.getElementById("questions");
                parent.removeChild(parent.lastChild);
                childCounter--;
            }
        }
    </script>
    <script src="utility.js"></script>
</head>

<body>
    <div id="wrapper">
        <form action="php_createQ.php" method="post">
        <div id="questionnaire-block">
            <input type="text" id="title" name="title" placeholder="Tytuł pytania">
            <input type="text" id="info"  name="info"  placeholder="Informacje na temat pytania">
            <div id="questions">
            </div>
        </div>
        <div id="buttons-block">
            <a href="admin.php">
            <div id="return" onclick="dim()">
                <img src="imgs/arrow128.png">
                <div>Powrót</div>
            </div>
            </a>
            <div id="add-remove-box">
                <img src="imgs/add.png"   class="add-remove-button" onclick="appendInput()">
                <img src="imgs/erase.png" class="add-remove-button" onclick="deleteInput()">
            </div>
            <label><input type="radio" name="state" value="1" checked><div class="radio_box">Ankieta widoczna <br> Głosy widoczne <br> Można głosować</div></label>
            <label><input type="radio" name="state" value="2"><div class="radio_box">Ankieta widoczna <br> Głosy niewidoczne <br> Można głosować</div></label>
            <label><input type="radio" name="state" value="3"><div class="radio_box">Ankieta widoczna <br> Głosy widoczne <br> Nie można głosować</div></label>
            <label><input type="radio" name="state" value="4"><div class="radio_box">Ankieta ukryta <br> Głosy niewidoczne <br> Nie można głosować</div></label>
            <input type="submit" id="done" value="Zakończ Tworzenie">
        </div>
        </form>
    </div>
</body>

</html>