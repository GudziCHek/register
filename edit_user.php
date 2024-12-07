<?php
// Проверяем наличие параметра user_id в URL
if (!isset($_GET['user_id'])) {
    die("Ошибка: ID пользователя не указан.");
}

// Получаем user_id из параметров URL
$user_id = $_GET['user_id'];

// Подключение к базе данных
$servername = "localhost";
$username = "admin";
$password = "password";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}

// Подготовленное выражение для запроса данных пользователя
$stmt = $conn->prepare("SELECT username, email, password FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username_from_database = $row["username"];
    $email_from_database = $row["email"];
    $password_from_database = $row["password"];
} else {
    die("Пользователь не найден");
}

$stmt->close(); // Закрываем запрос

// Отображение данных о пользователе в форме
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Редактирование пользователя</title>
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
    
    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    
    input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        display: block; /* Расположение кнопки на всю ширину контейнера */
        margin: 0 auto; /* Выравнивание по центру */
    }
    
    input[type="submit"]:hover {
        background-color: #0056b3;
    }
    </style>
</head>
<body>
    <div class='container'>
        <h2>Редактирование пользователя</h2>
        <form method='post' action='save_user.php'>
            <div class='form-group'>
                <input type='hidden' name='userId' value='<?php echo $user_id; ?>'>
                <label for='username'>Имя пользователя:</label>
                <input type='text' name='username' value='<?php echo $username_from_database; ?>'>
            </div>
            <div class='form-group'>
                <label for='email'>Email:</label>
                <input type='email' name='email' value='<?php echo $email_from_database; ?>'>
            </div>
            <div class='form-group'>
                <label for='password'>Пароль:</label>
                <input type='password' name='password' value='<?php echo $password_from_database; ?>'>
            </div>
            <input type='submit' name='edit' value='Сохранить'>
        </form>
    </div>
</body>
</html>
<?php
$conn->close(); // Закрываем соединение с базой данных
?>