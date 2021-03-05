<footer class="page-footer">
  <div class="container">
    <a class="page-footer__logo" href="#">
      <img src="/img/logo--footer.svg" alt="Fashion">
    </a>
    <nav class="page-footer__menu">
      <ul class="main-menu main-menu--footer">
        <?php foreach ($menuFooter as $key => $item): ?>
          <li>
            <a class="main-menu__item <?= isCurrentUrl($item['path']) ? 'active' : ''; ?>" href="<?= $item['path'] ?>">
              <?= $item['title'] ?>
            </a>
          </li>
        <?php endforeach ?>
      </ul>
    </nav>
    <address class="page-footer__copyright">
      © Все права защищены
    </address>
  </div>
</footer>
</body>
</html>