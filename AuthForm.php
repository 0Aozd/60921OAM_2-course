<?php
$cookie_lifetime = 12; // время жизни cookie (в секундах)
$uploadDir = "UserData/";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    //Сохраняем все поля в cookie
    foreach ($_POST as $key => $value) {
        if (is_array($value)) {
            setcookie($key, implode(", ", $value), time() + $cookie_lifetime);
        } else {
            setcookie($key, $value, time() + $cookie_lifetime);
        }
    }

    //Обработка загруженного файла
    if (!empty($_FILES["photo"]["name"])) {
        $fileName = basename($_FILES["photo"]["name"]);
        $targetFile = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            setcookie("uploadedFile", $targetFile, time() + $cookie_lifetime);
        }
    }


    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}


foreach ($_COOKIE as $key => $value) {
    setcookie($key, $value, time() + $cookie_lifetime);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация пользователя</title>
    <link rel="stylesheet" href="CSS/AuthForm.css">
</head>
<body>

<h2>Регистрация пользователя</h2>

<form action="" method="post" enctype="multipart/form-data">

    <label>Имя пользователя:</label><br>
    <input type="text" name="username" required value="<?= $_COOKIE['username'] ?? '' ?>"><br>

    <label>Email:</label><br>
    <input type="email" name="email" required value="<?= $_COOKIE['email'] ?? '' ?>"><br>

    <label>Пароль:</label><br>
    <input type="password" name="password" value="<?= $_COOKIE['password'] ?? '' ?>"><br><br>

    <label>Дата рождения:</label><br>
    <input type="date" name="birthdate" value="<?= $_COOKIE['birthdate'] ?? '' ?>"><br><br>

    <label>Загрузите фото профиля:</label><br>
    <input type="file" name="photo"><br><br>

    <label>Пол:</label><br>
    <?php
    $genders = ["Мужской", "Женский"];
    $gender = $_COOKIE['gender'] ?? '';
    foreach ($genders as $g) {
        $checked = ($g === $gender) ? "checked" : "";
        echo "<input type='radio' name='gender' value='$g' $checked> $g<br>";
    }
    ?><br>

    <label>Интересы:</label><br>
    <?php
    $interestsAll = ["Финансы", "Инвестиции", "Путешествия"];
    $interests = isset($_COOKIE['interests']) ? explode(", ", $_COOKIE['interests']) : [];
    foreach ($interestsAll as $i) {
        $checked = in_array($i, $interests) ? "checked" : "";
        echo "<input type='checkbox' name='interests[]' value='$i' $checked> $i<br>";
    }
    ?><br>

    <label>Валюты:</label><br>
    <select name="currencies[]" multiple size="3">
        <?php
        $currencies = ["RUB" => "Рубли", "USD" => "Доллары", "EUR" => "Евро", "JPY" => "Иена", "CNY" => "Юани"];
        $currSelected = isset($_COOKIE['currencies']) ? explode(", ", $_COOKIE['currencies']) : [];
        foreach ($currencies as $code => $name) {
            $sel = in_array($code, $currSelected) ? "selected" : "";
            echo "<option value='$code' $sel>$name</option>";
        }
        ?>
    </select><br><br>

    <label>О себе:</label><br>
    <textarea name="about" rows="5"><?= $_COOKIE['about'] ?? '' ?></textarea><br><br>

    <button type="submit">Зарегистрироваться</button>
</form>

<?php if (!empty($_COOKIE['username'])): ?>
    <hr>
    <h3>Введённые данные:</h3>
    <p><b>Имя:</b> <?= $_COOKIE["username"] ?></p>
    <p><b>Email:</b> <?= $_COOKIE["email"] ?></p>
    <p><b>Дата рождения:</b> <?= $_COOKIE["birthdate"] ?? "" ?></p>
    <p><b>Пол:</b> <?= $_COOKIE["gender"] ?? "" ?></p>
    <p><b>Интересы:</b> <?= $_COOKIE["interests"] ?? "" ?></p>
    <p><b>Валюты:</b> <?= $_COOKIE["currencies"] ?? "" ?></p>
    <p><b>О себе:</b> <?= nl2br($_COOKIE["about"] ?? "") ?></p>

    <?php if (!empty($_COOKIE['uploadedFile'])): ?>
        <p><b>Загруженное фото:</b></p>
        <img src="<?= $_COOKIE['uploadedFile'] ?>" width="150" style="border-radius: 10px;">
    <?php endif; ?>
<?php endif; ?>

</body>
</html>
