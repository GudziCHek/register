<?php
// Подключение к базе данных
$servername = "localhost";
$username = "admin";
$password = "password";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получение данных из формы регистрации
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Проверка, есть ли уже такой пользователь или email в базе данных
    $check_user_sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $check_result = $conn->query($check_user_sql);

    if ($check_result->num_rows > 0) {
        echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Ошибка при регистрации</title>
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
        <h2>Ошибка при регистрации</h2>
        <p>Пользователь с таким именем пользователя или email уже существует. Пожалуйста, повторите заново <a href='register.html'>регистрацию</a> и выберите другие данные.</p>
    </div>
</body>
</html>";
    } else {
        // Готовим SQL запрос для вставки данных
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Успешная регистрация</title>
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
        <h2>Успешная регистрация!</h2>
        <p>Хотите войти в систему? <a href='login.html'>Войти</a></p>
    </div>
</body>
</html>";
        } else {
            echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Ошибка при регистрации</title>
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
        <h2>Ошибка при регистрации</h2>
        <p>Произошла ошибка при регистрации. Пожалуйста, попробуйте <a href='register.html'>ещё раз</a> позже.</p>
    </div>
</body>
</html>";
        }
    }
}

$conn->close();
?>
