<?php
session_start();

// Подключение к базе данных
$servername = "localhost";
$username = "admin";
$password = "password";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получение данных из формы входа
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверка данных пользователя
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Аутентификация успешна
        $user_data = $result->fetch_assoc();

        // Сохранение информации о пользователе в сессию
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['access_level'] = $user_data['access_level'];

        // Проверяем уровень доступа
        if ($_SESSION['access_level'] === 'limited') {
        // Отладочный вывод
        echo "Уровень доступа пользователя: " . $_SESSION['access_level'];

        // Если уровень доступа - limited, перенаправляем на welcome.php
        header("Location: welcome.php");
        exit();
    } else {
        // Иначе, перенаправляем на страницу приветствия для пользователей с полным доступом
        header("Location: welcome.php");
        exit();
        }
    } else {
        // Неправильный логин или пароль
        echo "
        <!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Ошибка входа</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }
    
    .container {
        width: 400px;
        margin: 100px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    
    h2 {
        text-align: center;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    label {
        font-weight: bold;
    }
    
    input[type=\"text\"],
    input[type=\"email\"],
    input[type=\"password\"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    
    input[type=\"submit\"] {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    input[type=\"submit\"]:hover {
        background-color: #0056b3;
    }
    </style>
</head>
<body>
    <div class='container'>
        <h2>Ошибка входа</h2>
        <p>Введенные Вами данные неверны! Повторите снова <a href='login.html'>вход</a> в систему.</p>
    </div>
</body>
</html>
        ";
    }
}

$conn->close();
?>