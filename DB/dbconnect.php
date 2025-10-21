<?php
try {
    $conn = new PDO("pgsql:host=localhost;port=5432;dbname=easymoney;user=postgres;password=Adamgrozny9586");

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    echo "Ошибка подключения к БД: " . $e->getMessage();
    die();
}
?>

