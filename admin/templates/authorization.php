<main class="page-authorization">
  <h1 class="h h--1">Авторизация</h1>
  <?php if (!empty($_SESSION['inputLogin'])): ?>
    <h2>Неверный логин или пароль!</h2>
  <?php endif ?>
  <form class="custom-form" action="/admin/" method="post">
    <input type="email" class="custom-form__input" name="login" required="" placeholder="Логин - email" value="<?= $inputLogin ?>">
    <input type="password" class="custom-form__input" name="password" required="" placeholder="Пароль" value="<?= $inputPassword ?>">
    <button class="button" type="submit" name="submit">Войти в личный кабинет</button>
  </form>
</main>
