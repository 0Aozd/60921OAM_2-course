<?php
session_start(["use_strict_mode" => true]);
unset($_SESSION['message']);
if (isset($_POST['login'])){
    $_SESSION['login_value'] = $_POST['login'];
    if ($_POST['login'] == 'Max'){
        if ($_POST['password'] == '412412'){
            $_SESSION['username'] = $_POST['login'];
            unset($_SESSION['login_value']);
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
