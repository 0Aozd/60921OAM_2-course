<?php
$cookie_lifetime = 12;
$uploadDir = "UserData/";


// Обработка POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Сохраняем все поля в cookie
    foreach ($_POST as $key => $value) {
        if (is_array($value)) {
            setcookie($key, implode(",", $value), time() + $cookie_lifetime);
        } else {
            setcookie($key, $value, time() + $cookie_lifetime);
        }
    }

    // Обработка загруженного файла
    if (!empty($_FILES["photo"]["name"])) {
        $fileName = basename($_FILES["photo"]["name"]);
        $targetFile = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            setcookie("uploadedFile", $targetFile, time() + $cookie_lifetime);
        }
    }

    // Redirect после POST
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

//Получаем данные из cookie
$formData = [];
$data = ['username','email','password','birthdate','gender','interests','currencies','about'];
foreach($data as $d){
    if(isset($_COOKIE[$d])){
        if(in_array($d, ['interests','currencies'])) $formData[$d] = explode(",", $_COOKIE[$d]);
        else $formData[$d] = $_COOKIE[$d];

        if (is_array($formData[$d])) {
            setcookie($d, implode(",", $formData[$d]), time() + $cookie_lifetime);
        } else {
            setcookie($d, $formData[$d], time() + $cookie_lifetime);
        }
    }
}

// Продлеваем cookie для загруженного файла
$uploadedFilePath = $_COOKIE['uploadedFile'] ?? "";
if($uploadedFilePath) setcookie("uploadedFile", $uploadedFilePath, time() + $cookie_lifetime);

// Функции для удобного получения значений
function getVal($name, $formData){
    return $formData[$name] ?? '';
}
function getArr($name, $formData){
    return $formData[$name] ?? [];
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
    <input type="text" name="username" required value="<?= getVal('username', $formData) ?>"><br>

    <label>Email:</label><br>
    <input type="email" name="email" required value="<?= getVal('email', $formData) ?>"><br>

    <label>Пароль:</label><br>
    <input type="password" name="password" value="<?= getVal('password', $formData) ?>"><br><br>

    <label>Дата рождения:</label><br>
    <input type="date" name="birthdate" value="<?= getVal('birthdate', $formData) ?>"><br><br>

    <label>Загрузите фото профиля:</label><br>
    <input type="file" name="photo"><br><br>

    <label>Пол:</label><br>
    <?php
    $genders = ["Мужской","Женский"];
    $userGender = getVal('gender', $formData);

    foreach($genders as $g){
        $checked = ($userGender !== "" && $g === $userGender) ? "checked" : "";
        echo "<input type='radio' name='gender' value='$g' $checked> $g<br>";
    }
    ?><br>

    <label>Интересы:</label><br>
    <?php
    $interests = ["Финансы","Инвестиции","Путешествия"];
    $interestsSelected = getArr('interests', $formData);
    foreach($interests as $i){
        $checked = in_array($i, $interestsSelected) ? "checked" : "";
        echo "<input type='checkbox' name='interests[]' value='$i' $checked> $i<br>";
    }
    ?><br>

    <label>Валюты:</label><br>
    <select name="currencies[]" multiple size="3">
        <?php
        $currencies = ["RUB"=>"Рубли","USD"=>"Доллары","EUR"=>"Евро","JPY"=>"Иена","CNY"=>"Юани"];
        $currSelected = getArr('currencies', $formData);
        foreach($currencies as $code=>$name){
            $sel = in_array($code,$currSelected) ? "selected" : "";
            echo "<option value='$code' $sel>$name</option>";
        }
        ?>
    </select><br><br>

    <label>О себе:</label><br>
    <textarea name="about" rows="5"><?= getVal('about', $formData) ?></textarea><br><br>

    <button type="submit">Зарегистрироваться</button>
</form>

<?php if (!empty($formData)): ?>
    <hr>
    <h3>Введённые данные:</h3>
    <p><b>Имя:</b> <?= $formData["username"] ?></p>
    <p><b>Email:</b> <?= $formData["email"] ?></p>
    <p><b>Дата рождения:</b> <?= $formData["birthdate"] ?? "" ?></p>
    <p><b>Пол:</b> <?= $formData["gender"] ?? "" ?></p>
    <p><b>Интересы:</b> <?= implode(", ", $interestsSelected) ?></p>
    <p><b>Валюты:</b> <?= implode(", ", $currSelected) ?></p>
    <p><b>О себе:</b> <?= isset($formData["about"]) ? nl2br($formData["about"]) : "" ?></p>

    <?php if (!empty($uploadedFilePath)): ?>
        <p><b>Загруженное фото:</b></p>
        <img src="<?= $uploadedFilePath ?>" width="150" style="border-radius: 10px;">
    <?php endif;?>
<?php endif; ?>

</body>
</html>
