<?php
// Проверка сессии и уровня доступа
session_start();
if (!isset($_SESSION['access_level'])) {
    header("Location: login.php"); // Перенаправление на страницу входа, если уровень доступа не установлен
    exit;
}

// Подключение к базе данных
$servername = "localhost";
$username = "admin";
$password = "password";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Обработка POST запросов (добавление, удаление, редактирование)
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['access_level'] === 'full') {
    // Добавление новой записи
    if (isset($_POST['add'])) {
        // Обработка данных из формы
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Готовим SQL запрос для вставки данных
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            // Перенаправляем обратно на страницу с сообщением об успешном добавлении
            header("Location: welcome.php?success=add");
            exit;
        } else {
            // Если произошла ошибка в запросе
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Удаление записи
    if (isset($_POST['delete'])) {
        // Получаем ID записи для удаления
        $user_id = $_POST['user_id'];

        // Готовим SQL запрос для удаления записи
        $sql = "DELETE FROM users WHERE id='$user_id'";

        if ($conn->query($sql) === TRUE) {
            // Перенаправляем обратно на страницу с сообщением об успешном удалении
            header("Location: welcome.php?success=delete");
            exit;
        } else {
            // Если произошла ошибка в запросе
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Редактирование записи
    if (isset($_POST['edit'])) {
        // Обработка данных из формы
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Готовим SQL запрос для обновления данных
        $sql = "UPDATE users SET username='$username', email='$email', password='$password' WHERE id='$user_id'";

        if ($conn->query($sql) === TRUE) {
            // Перенаправляем обратно на страницу с сообщением об успешном обновлении
            header("Location: welcome.php?success=edit");
            exit;
        } else {
            // Если произошла ошибка в запросе
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Запрос на получение всех записей из таблицы
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1, h2 {
    text-align: center;
}

form {
    margin-bottom: 20px;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box; /* Учитываем padding в ширине */
}

button[type="submit"] {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
}

p {
    margin-top: 20px;
    padding: 10px;
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 5px;
    color: #155724;
}

a {
    display: block;
    text-align: center;
    margin-top: 20px;
    text-decoration: none;
    color: #007bff;
}

a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>
    <h1>Добро пожаловать, <?php echo $_SESSION['username']; ?>!</h1>

    <?php if ($_SESSION['access_level'] === 'full') : ?>
    <!-- Форма для добавления новой записи -->
    <form method="post">
        <h2>Добавить нового пользователя:</h2>
        <input type="text" name="username" placeholder="Имя пользователя" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Пароль" required><br>
        <button type="submit" name="add">Добавить</button>
    </form>
    <?php endif; ?>

    <?php
    // Выводим сообщение об успешном добавлении, если таковое имеется
    if (isset($_GET['success']) && $_GET['success'] == 'add') {
        echo "<p>Пользователь успешно добавлен!</p>";
    }
    ?>

<?php
// Таблица с существующими записями
echo "<h2>Список пользователей:</h2>";
echo "<table border='1'>";
echo "<tr>";
echo "<th>ID</th>";
echo "<th>Имя пользователя</th>";
echo "<th>Email</th>";
echo "<th>Пароль</th>";
echo "<th>Действия</th>";
echo "</tr>";

// Получаем данные о пользователях из базы данных
// Подключение к базе данных и запрос пользователей
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    // Вывод информации о пользователе
    echo "<tr>";
    echo "<td>" . $row["id"] . "</td>";
    echo "<td>" . $row["username"] . "</td>";
    echo "<td>" . $row["email"] . "</td>";
    echo "<td>" . $row["password"] . "</td>";
    echo "<td>";
    if ($_SESSION['access_level'] === 'full') {
        // Форма для редактирования
        echo "<form method='get' action='edit_user.php'>";
        echo "<input type='hidden' name='user_id' value='" . $row["id"] . "'>";
        echo "<button type='submit'>Редактировать</button>";
        echo "</form>";
        // Форма для удаления
        echo "<form method='post' style='display: inline;'>";
        echo "<input type='hidden' name='user_id' value='" . $row["id"] . "'>";
        echo "<button type='submit' name='delete'>Удалить</button>";
        echo "</form>";
    } else {
        echo "Действия недоступны";
    }
    echo "</td>";
    echo "</tr>";
}

echo "</table>";

// Выводим сообщение об успешном удалении или редактировании, если таковое имеется
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'delete') {
        echo "<p>Пользователь успешно удален!</p>";
    } elseif ($_GET['success'] == 'edit') {
        echo "<p>Пользователь успешно отредактирован!</p>";
    }
}
?>

<a href="login.html">Выход из системы</a>
</body>
</html>

<?php
$conn->close();
?>
