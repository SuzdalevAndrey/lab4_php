# Система управления библиотекой
Это простая система управления библиотекой, реализованная с использованием PHP и MySQL. 
Система включает страницы "Читатели", "Книги", страницу аутентификации и личный кабинет. 
Поддерживаются два уровня доступа: "Читатели" и "Администраторы".

## Возможности
1. Личный кабинет читателя:

* Просмотр взятых книг.
* Получение предупреждений, если срок возврата книги близится.
* Возможность взятия книги
2. Функции администраторов:

* Редактирование каталога библиотеки.
* Блокировка учетных записей пользователей.
## Единая навигация:

Все страницы используют одинаковое меню и заголовок.
## Страницы
1. Читатели
   
Отображает таблицу с списком читателей, включая их ID, имя и фамилию.
Аутентифицированные администраторы видят расширенную таблицу с информацией о взятых книгах и пользователях с просроченными книгами.

2. Книги

Отображает таблицу с списком книг, включая их ID, название, год публикации и статус (взята или доступна). 
Если книга взята, также отображается фамилия текущего читателя и дата взятия.

## Аутентификация
Позволяет пользователям войти с использованием своих учетных данных. 
Пользователи перенаправляются на свои личные кабинеты в зависимости от своей роли.
