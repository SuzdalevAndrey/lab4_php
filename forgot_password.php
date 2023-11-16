<?php
require_once('fullContent.php');
?>


<?php
// Подключение к базе данных
require_once('db_connection.php');

// Обработка POST-запроса, отправленного после ввода логина
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["recover_password"])) {
    $username = $_POST["username"];
    $new_password = $_POST["new_password"];

    // Проверка существования пользователя с введенным логином
    $stmt_check_user = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt_check_user->execute([$username]);
    $existingUser = $stmt_check_user->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        // Обновление пароля пользователя
        $stmt_update_password = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt_update_password->execute([password_hash($new_password, PASSWORD_DEFAULT), $username]);

        echo "Пароль успешно обновлен.";
    } else {
        echo "Пользователь с таким логином не найден.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Восстановление пароля</title>
</head>
<body>

<div class="content">
    <h2>Восстановление пароля</h2>

    <form method="post" action="">
        <label for="recover-username">Логин:</label>
        <input type="text" id="recover-username" name="username" required><br><br>
        <label for="new-password">Новый пароль:</label>
        <input type="password" id="new-password" name="new_password" required><br><br>
        <input type="submit" name="recover_password" value="Восстановить пароль">
    </form>
    <form method="post" action="logout.php">
        <input type="submit" name="logout" value="Вернуться на страницу входа">
    </form>
</div>

</body>
</html>
