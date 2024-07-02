<?php
// Параметры подключения к базе данных
$host = 'localhost'; // Хост базы данных
$dbName = 'models'; // Имя базы данных
$username = 'root'; // Имя пользователя MySQL
$password = ''; // Пароль MySQL

try {
    // Создание подключения
    $pdo = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);

    // Установка режима обработки ошибок PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Установка кодировки символов
    $pdo->exec("SET NAMES utf8");

} catch (PDOException $e) {
    // Вывод ошибки подключения
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
}
?>
