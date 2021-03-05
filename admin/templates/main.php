<main class="page-order">
    <h1 class="h h--1">Админ-панель</h1>
    <p>Имя пользователя - <?= $_SESSION['user']['name'] ?></p>
    <p>E-Mail - <?= $_SESSION['user']['email'] ?></p>
    <p>Ваши роли - <?= implode(", ", array_column($_SESSION['groups'], 'name')) ?></p>
</main>