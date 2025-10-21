<?php
global $conn;
session_start(["use_strict_mode" => true]);
require('DB/dbconnect.php');
unset($_SESSION['message']);
if (isset($_POST['login'])){
    $result = $conn->query("SELECT * FROM users WHERE email = '".$_POST['login']."'");

    if ($row = $result->fetch())
    {
        if (MD5($_POST["password"]) == $row['password']){ #213123
            $_SESSION['username'] = $row['name'];
            header("Location: Main.php");
            die();
        }
        else {
            $_SESSION['message'] = 'Вы ввели неправильный пароль!';
            header("Location: Main.php");
            die();
        }

    }
    else {
        $_SESSION['message'] = 'Вы ввели неправильный логин!';
        header("Location: Main.php");
        die();
    }

}
if (isset($_GET['logout']) && $_GET['logout'] == 1){
    session_unset();
    $_SESSION['message'] = 'Вы успешно вышли из системы';
    header("Location: Main.php");
    die();
}
