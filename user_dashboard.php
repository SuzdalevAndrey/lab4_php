<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once('fullContent.php');
require_once('db_connection.php');

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



<div class="content">
    <h2>Личный кабинет</h2>
    <p>Привет, <?php echo $_SESSION['username']; ?>!
    Статус аккаунта: <?php echo $_SESSION['role']; ?></p>

<?php   
if($_SESSION['role']=='admin'){
    // Обработка редактирования библиотеки
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_library"])) {
        $book_id = $_POST["book_id"];
        $new_book_name = $_POST["new_book_name"];
        $new_pub_year = $_POST["new_pub_year"];

        $stmt = $pdo->prepare("UPDATE books SET name = ?, pub_year = ? WHERE id = ?");
        $stmt->execute([$new_book_name, $new_pub_year, $book_id]);

        echo "Информация о книге успешно обновлена.";
    }
    // Обработка блокировки учетной записи
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_user"])) {
        $user_id_to_delete = $_POST["user_id_to_delete"];
    
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id_to_delete]);
    
        echo "Учетная запись пользователя удалена.";
    }
    // Добвление новой книги
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_book"])) {
        $new_book_name = $_POST["new_book_name"];
        $new_pub_year = $_POST["new_pub_year"];
        $new_book_id = $_POST["new_book_id"];
    
        $stmt = $pdo->prepare("INSERT INTO books (id, name, pub_year) VALUES (?, ?, ?)");
        $stmt->execute([$new_book_id, $new_book_name, $new_pub_year]);
    
        echo "Новая книга успешно добавлена.";
    }
        // Удаление книги
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_book"])) {
        $book_id_to_delete = $_POST["book_id_to_delete"];
    
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$book_id_to_delete]);
    
        echo "Книга удалена.";
    }
}
?>

<div class="content">
    <form method="post" action="">
        <h3>Редактировать библиотеку</h3>
        <label for="book_id">ID книги:</label>
        <input type="text" id="book_id" name="book_id" required><br>
        <label for="new_book_name">Новое название книги:</label>
        <input type="text" id="new_book_name" name="new_book_name" required><br>
        <label for="new_pub_year">Новый год публикации:</label>
        <input type="text" id="new_pub_year" name="new_pub_year" required><br>
        <input type="submit" name="edit_library" value="Обновить информацию о книге">
    </form>

    <form method="post" action="">
        <h3>Заблокировать учетную запись</h3>
        <label for="user_id_to_delete">ID пользователя:</label>
        <input type="text" id="user_id_to_delete" name="user_id_to_delete" required><br>
        <input type="submit" name="delete_user" value="Заблокировать учетную запись">
    </form>

    <form method="post" action="">
        <h3>Удалить книгу</h3>
        <label for="book_id_to_delete">ID книги:</label>
        <input type="text" id="book_id_to_delete" name="book_id_to_delete" required><br>
        <input type="submit" name="delete_book" value="Удалить книгу">
    </form>
    <form method="post" action="">
        <h3>Добавить новую книгу</h3>
        <label for="new_book_id">ID книги:</label>
        <input type="text" id="new_book_id" name="new_book_id" required><br>
        <label for="new_book_name">Название книги:</label>
        <input type="text" id="new_book_name" name="new_book_name" required><br>
        <label for="new_pub_year">Год публикации:</label>
        <input type="text" id="new_pub_year" name="new_pub_year" required><br>
        <input type="submit" name="add_book" value="Добавить книгу">
    </form>
    <form method="post" action="logout.php">
        <input type="submit" name="logout" value="Выйти">
    </form>

</div>
</body>
</html>
