<?php
require_once('db_connection.php');
require_once('fullContent.php');


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = $_POST["role"];
    $last_name = $_POST["last_name"];
    $first_name = $_POST["first_name"];

    // Проверка наличия пользователя с таким именем
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$existingUser) {
        // Регистрация нового пользователя с указанием роли
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, $role]);

        $user_id = $pdo->lastInsertId();

        if ($_SESSION['role'] === 'reader') {
            // Добавление данных в таблицу readers
            $stmt_readers = $pdo->prepare("INSERT INTO readers (id, last_name, first_name) VALUES (?, ?, ?)");
            $stmt_readers->execute([$user_id, $last_name, $first_name]);
        }

        // Автоматическая авторизация после успешной регистрации
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        header("Location: user_dashboard.php");
        exit;
    } else {
        $errorMessage = "Пользователь с таким именем уже существует.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Регистрация</title>
</head>
<body>



<div class="content">
    <h2>Регистрация</h2>
    <form method="post" action="">
    <h3>Регистрация</h3>
    <label for="register-username">Логин:</label>
    <input type="text" id="register-username" name="username" required><br>
    <br>
    <label for="register-password">Пароль:</label>
    <input type="password" id="register-password" name="password" required><br>
    <br>
    <label for="user-role">Роль:</label>
    <select id="user-role" name="role" required>
        <option value="reader">Читатель</option>
        <option value="admin">Администратор</option>
    </select><br><br>
    <label for="first-name">Имя:</label>
    <input type="text" id="first-name" name="first_name" required><br>
    <br>
    <label for="last-name">Фамилия:</label>
    <input type="text" id="last-name" name="last_name" required><br>
    <br>
    <input type="submit" name="register" value="Зарегистрироваться">
</form>
<br>
<form method="post" action="logout.php">
    <input type="submit" name="logout" value="Вернуться на страницу входа">
</form>
    <?php
    if (isset($errorMessage)) {
        echo "<p class='error'>$errorMessage</p>";
    }
    ?>
</div>

</body>
</html>
