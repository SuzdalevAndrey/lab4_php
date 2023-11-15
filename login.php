<?php
require_once('db_connection.php');

session_start();

// Функция для проверки аутентификации пользователя
function checkAuthentication() {
    if (isset($_SESSION['username'])) {
        header("Location: user_dashboard.php"); // Перенаправление на личный кабинет
        exit;
    }
}

// Проверка аутентификации при загрузке страницы
checkAuthentication();

// Проверка входа пользователя при отправке формы авторизации
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: user_dashboard.php");
        exit;
    } else {
        $errorMessage = "Неправильный логин или пароль.";
    }
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

    <?php if (isset($_SESSION['username'])): ?>
        <p>Привет, <?php echo $_SESSION['username']; ?>!</p>
        <p>Статус аккаунта: <?php echo $_SESSION['role']; ?></p>
        <!-- Добавьте остальное содержимое личного кабинета здесь -->
    <?php else: ?>
        <!-- Форма авторизации -->
        <form method="post" action="">
            <h3>Авторизация</h3>
            <label for="login-username">Логин:</label>
            <input type="text" id="login-username" name="username" required><br>
            <br>
            <label for="login-password">Пароль:</label>
            <input type="password" id="login-password" name="password" required><br>
            <br>
            <input type="submit" name="login" value="Войти">
        </form>
        <br>
        <p>У вас нет аккаунта? <a href="register.php">Зарегистрируйтесь!</a></p>
        <?php if (isset($errorMessage)): ?>
            <p class='error'><?php echo $errorMessage; ?></p>
        <?php endif; ?>
    <?php endif; ?>

</div>

</body>
</html>
