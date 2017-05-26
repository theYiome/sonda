<?php
session_start();
if(isset($_SESSION['logged_as'])){
    header('Location: admin.php'); 
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Kalam&subset=latin-ext" rel="stylesheet">
    <link rel="stylesheet" href="styles/global.css" type="text/css">
    <link rel="stylesheet" href="login_page.css" type="text/css">
</head>

<body>
    <div id="wrapper">
        <div id="top_bar">
            <a href="default.php">
            <div id="return">
                <img src="imgs/arrow128.png" width="100" height="100">
                <div style="display: inline-block; position: relative; left: -15px; top: -25px;">Powrót</div>
            </div>
            </a>

            <div id="title">
                <img class="hvr-wobble-vertical" src="imgs/config256.png" width="100" height="100">
                Zarządzanie
            </div>

        </div>

        <div id="login_box">
            <div id="imputs">
                <form action="php_login.php" method="post">
                <label> <input type="text" name="login" placeholder="Login">        </label>
                <label> <input type="password" name="password" placeholder="Hasło"> </label>
                <input class="hvr-wobble-vertical" type="submit" value="Zaloguj">
                </form>
            </div>

            <div id="login_msg">
                <?php
                session_start();
                if(isset($_SESSION['login_msg'])){
                    echo $_SESSION['login_msg'];
                    unset($_SESSION['login_msg']);
                } 
                ?>
            </div>
        </div>

        <div id="info">
            <p>Jest to storna logowania dla administratora serwisu, 
                będącego we władzy tworzenia, 
                edytowania, ukrywania oraz usuwania istniejących już ankiet.</p>
            <p>Jeżeli nim nie jesteś to nie masz tu czego szukać.</p>
        </div>

    </div>
</body>
</html>
