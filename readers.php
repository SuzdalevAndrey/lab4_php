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
require_once('db_connection.php');
require_once('fullContent.php');

session_start();

// Проверяем уровень доступа пользователя
if ($_SESSION['role'] === 'reader') {
    $query = "SELECT lt.book_id, b.name, lt.taken_at, lt.returned_at
            FROM php_Suzdalev.log_taking lt
            INNER JOIN php_Suzdalev.books b ON lt.book_id = b.id
            INNER JOIN php_Suzdalev.users u ON lt.reader_id = u.id
            WHERE u.username = :username";

    $statement = $pdo->prepare($query);
    $statement->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
    $statement->execute();
    $takenBooks = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Выводим взятые книги
    echo '<div class="content">';
    echo '<h2>Ваши книги</h2>';
    if (count($takenBooks) > 0) {
        echo '<table>';
        echo '<tr><th>Название книги</th><th>Дата взятия</th><th>Дата возврата</th></tr>';
        foreach ($takenBooks as $book) {
            echo '<tr>';
            echo '<td>' . $book['name'] . '</td>';
            echo '<td>' . $book['taken_at'] . '</td>';
            echo '<td>' . ($book['returned_at'] ?? 'Не возвращена') . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>У вас нет взятых книг.</p>';
    }
    echo '</div>';

    // Добавим форму для продления книги
    echo '<div class="content">';
    echo '<h2>Продление книги</h2>';
    echo '<form method="post" action="process_extension.php">';
    echo '<label for="book_id">Выберите книгу для продления:</label>';
    echo '<select name="book_id">';
    foreach ($takenBooks as $book) {
        if ($book['returned_at'] === null) {
            echo '<option value="' . $book['book_id'] . '">' . $book['name'] . '</option>';
        }
    }
    echo '</select>';
    echo '<input type="submit" name="extend" value="Продлить">';
    echo '</form>';
    echo '</div>';
} else if($_SESSION['role']==='admin'){
    $query = "SELECT * FROM readers";
    $statement = $pdo->query($query);
    $readers = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo'<div class="content">';
        echo' <h2>Список читателей</h2>';
        echo'<table>
            <tr>
                <th>ID</th>
                <th>Фамилия</th>
                <th>Имя</th>
            </tr>';
            foreach ($readers as $reader):
                echo'<tr>';
                    echo'<td>'.$reader['id'].'</td>';
                    echo'<td>'. $reader['last_name'].'</td>';
                    echo'<td>'. $reader['first_name'].'</td>';
                echo'</tr>';
            endforeach;
        echo'</table>';
    echo'</div>
    </body>
    </html>';
}
?>

</body>
</html>
