<?php
$users = [
    'reader' => [
        'password' => password_hash('readerpassword', PASSWORD_DEFAULT),
        'isAdmin' => false,
        'books' => [
            ['title' => 'Book 1', 'due_date' => '2023-01-15'],
            ['title' => 'Book 2', 'due_date' => '2023-02-20']
        ]
    ],
    'admin' => [
        'password' => password_hash('adminpassword', PASSWORD_DEFAULT),
        'isAdmin' => true,
    ],
];
?>
