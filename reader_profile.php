<?php
require_once('users.php');
session_start();

if (!isset($_SESSION['username']) || !$_SESSION['isAdmin']) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$user = $users[$username];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Личный кабинет читателя</title>
</head>
<body>

<?php
require_once('fullContent.php');
?>

<div class="content">
    <h2>Личный кабинет читателя</h2>
    <p>Имя: <?php echo $username; ?></p>
    <h3>Ваши книги:</h3>
    <ul>
        <?php foreach ($user['books'] as $book): ?>
            <li><?php echo $book['title']; ?> (до <?php echo $book['due_date']; ?>)</li>
        <?php endforeach; ?>
    </ul>
</div>

</body>
</html>
