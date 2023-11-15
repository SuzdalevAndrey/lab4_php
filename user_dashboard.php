<?php
session_start();

// Проверка аутентификации пользователя
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Если пользователь не авторизован, перенаправляем на страницу авторизации
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Личный кабинет</title>
</head>
<body>

<?php
require_once('fullContent.php');
?>

<div class="content">
    <h2>Личный кабинет</h2>

    <p>Привет, <?php echo $_SESSION['username']; ?>!</p>
    <p>Статус аккаунта: <?php echo $_SESSION['role']; ?></p>

    <!-- Кнопка выхода из аккаунта -->
    <form method="post" action="logout.php">
        <input type="submit" name="logout" value="Выйти">
    </form>
</div>

</body>
</html>
