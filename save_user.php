<?php
// Подключение к базе данных
$servername = "localhost";
$username = "admin";
$password = "password";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['userId'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Выполняем операции обновления данных о пользователе в базе данных
    $sql = "UPDATE users SET username='$username', email='$email', password='$password' WHERE id='$userId'";

    if ($conn->query($sql) === TRUE) {
        // Ваш код, если обновление прошло успешно
        echo "Данные пользователя успешно обновлены!";

        // Перенаправление обратно на страницу welcome.php с сообщением об успешном обновлении
        header("Location: welcome.php?success=edit");
        exit;
    } else {
        // Ваш код, если произошла ошибка при обновлении
        echo "Ошибка при обновлении данных: " . $conn->error;
    }
}

$conn->close(); // Закрываем соединение с базой данных
?>
