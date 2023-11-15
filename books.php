<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Книги</title>
</head>
<body>

<?php
require_once('db_connection.php');
require_once('fullContent.php');

session_start();

// Обработка взятия книги
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["take_book"])) {
    $book_id = $_POST["book_id"];

    // Проверим, не взята ли книга уже
    $check_query = "SELECT * FROM log_taking WHERE book_id = ? AND returned_at IS NULL";
    $check_statement = $pdo->prepare($check_query);
    $check_statement->execute([$book_id]);
    $already_taken = $check_statement->fetch();

    if (!$already_taken) {
        // Получим user_id из таблицы users по логину
        $user_login = $_SESSION['username'];

        $get_user_id_query = "SELECT id FROM users WHERE username = ?";
        $get_user_id_statement = $pdo->prepare($get_user_id_query);
        $get_user_id_statement->execute([$user_login]);
        $user_data = $get_user_id_statement->fetch();

        if ($user_data && isset($user_data['id'])) {
            $user_id = $user_data['id'];

            // Выполним вставку записи в таблицу log_taking
            $insert_query = "INSERT INTO log_taking (reader_id, book_id, taken_at) VALUES (?, ?, NOW())";
            $insert_statement = $pdo->prepare($insert_query);
            $insert_statement->execute([$user_id, $book_id]);
        } else {
            echo "Error: User ID not found for login '$user_login'.";
        }
    }
}



$query = "SELECT b.id, b.name, b.pub_year, 
                 r.last_name AS reader_last_name, r.first_name AS reader_first_name, 
                 lt.taken_at
          FROM books b
          LEFT JOIN log_taking lt ON b.id = lt.book_id
          LEFT JOIN readers r ON lt.reader_id = r.id";

$statement = $pdo->query($query);
$books = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="content">
    <h2>Список книг</h2>

    <?php if ($_SESSION['role'] === 'reader'): ?>
        <!-- Личный кабинет читателя -->
        <form method="post" action="">
            <table>
                <tr>
                    <th>Номер</th>
                    <th>Название</th>
                    <th>Год публикации</th>
                    <th>Доступность</th>
                    <th>Действие</th>
                </tr>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?php echo $book['id']; ?></td>
                        <td><?php echo $book['name']; ?></td>
                        <td><?php echo $book['pub_year']; ?></td>
                        <td>
                            <?php 
                                if ($book['reader_last_name'] && $book['reader_first_name']) {
                                    echo 'Недоступна';
                                } else {
                                    echo 'Доступна';
                                }
                            ?>
                        </td>
                        <td>
                            <?php if (!$book['reader_last_name'] && !$book['reader_first_name']): ?>
                                <input type="submit" name="take_book" value="Взять" />
                                <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>" />
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </form>
    <?php elseif ($_SESSION['role'] === 'admin'): ?>
        <!-- Личный кабинет администратора -->
        <table>
            <tr>
                <th>Номер</th>
                <th>Название</th>
                <th>Год публикации</th>
                <th>Читатель</th>
                <th>Дата взятия</th>
            </tr>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?php echo $book['id']; ?></td>
                    <td><?php echo $book['name']; ?></td>
                    <td><?php echo $book['pub_year']; ?></td>
                    <td><?php echo ($book['reader_last_name'] && $book['reader_first_name']) ? $book['reader_last_name'] . ' ' . $book['reader_first_name'] : 'Доступна'; ?></td>
                    <td><?php echo $book['taken_at']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <?php header("Location: login.php"); exit; ?>
    <?php endif; ?>

</div>

</body>
</html>
