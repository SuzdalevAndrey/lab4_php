<?php
session_start();

// Уничтожаем сессию при выходе из аккаунта
session_destroy();

// Перенаправляем на страницу авторизации
header("Location: login.php");
exit;
?>
