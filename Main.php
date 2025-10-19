<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>EasyMoney</title>
    <link rel="stylesheet" href="CSS/AuthForm.css">
</head>
<body>

<h2>Добро пожаловать в EasyMoney</h2>

<?php
session_start(["use_strict_mode" => true]);
if (isset($_SESSION['username'])) {

    ?>

    <p>Вы вошли под именем <?=$_SESSION['username']?></p>
    <p><a href='auth.php?logout=1'>Выйти</a></p>

<?php }
else{
    ?>

    <form method="post" action="auth.php">
        <div>
            <label for="id1">Логин:</label><br>
            <input name="login" id="id1" type="text" size="20" maxlength="40"
                   value="<?= $_SESSION['login_value'] ?? '' ?>">
        </div>
        <div>
            <label for="id2">Пароль:</label><br>
            <input name="password" id="id2" type="password" size="20" maxlength="40" >
        </div>
        <div>
            <button type="submit">Войти</button>
        </div>
        <?php
            if (!empty($_SESSION['message'])) {
            echo("<p style='color: red'>" . $_SESSION['message'] . "</p>");
            unset($_SESSION['login_value']);
            unset($_SESSION['message']);
            }
        ?>
    </form>

<?php }

?>

</body>
</html>
