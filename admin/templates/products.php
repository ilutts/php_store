<?php if (!isset($_GET['add'])) { ?>
  <main class="page-products">
    <h1 class="h h--1">Товары</h1>
    <a class="page-products__button button" href="/admin/?category=products&add">Добавить товар</a>
    <div class="page-products__header">
      <span class="page-products__header-field">Название товара</span>
      <span class="page-products__header-field">ID</span>
      <span class="page-products__header-field">Цена</span>
      <span class="page-products__header-field">Категории</span>
      <span class="page-products__header-field">Акции</span>
    </div>
    <ul class="page-products__list">
    <?php foreach ($productsShop as $id => $product): ?>
      <li class="product-item page-products__item">
        <b class="product-item__name"><?= $product['name'] ?></b>
        <span class="product-item__field product-item__field--id"><?= $id ?></span>
        <span class="product-item__field"><?= $product['price'] ?></span>
        <span class="product-item__field"><?= $product['category'] ?></span>
        <span class="product-item__field"><?= $product['promo'] ?></span>
        <a href="/admin/?category=products&add&change-id=<?= $id ?>" class="product-item__edit" aria-label="Редактировать"></a>
        <button class="product-item__delete" aria-label="Удалить"></button>
      </li>
    <?php endforeach ?>
    </ul>
    <ul class="shop__paginator paginator">
      <?php for ($i = 1; $i <= $quantityPages; $i++): ?>
          <li>
            <a class="paginator__item" <?= getStatusPage($i) ?>><?= $i ?></a>
          </li>
      <?php endfor ?>
    </ul>
  </main>
<?php } else { ?>
  <main class="page-add">
    <h1 class="h h--1"><?= isset($_GET['change-id']) ? 'Изменение' : 'Добавление' ?> товара</h1>
    <form class="custom-form">
      <fieldset class="page-add__group custom-form__group">
        <legend class="page-add__small-title custom-form__title">Данные о товаре <?= $productChange['id'] ?? '' ?></legend>
        <p class="custom-form__info">
          <span class="req">*</span> поля обязательные для заполнения
        </p>
        <label for="product-name" class="custom-form__input-wrapper page-add__first-wrapper">
          <input type="text" class="custom-form__input" name="product-name" id="product-name" value="<?= $productChange['name'] ?? '' ?>" required>
          <?php if (!isset($_GET['change-id'])): ?>
            <p class="custom-form__input-label">
              Название товара <span class="req">*</span>
            </p>
          <?php endif ?>
        </label>
        <label for="product-price" class="custom-form__input-wrapper">
          <input type="text" class="custom-form__input" name="product-price" id="product-price" value="<?= $productChange['price'] ?? '' ?>" required="">
          <?php if (!isset($_GET['change-id'])): ?>
          <p class="custom-form__input-label">
            Цена товара <span class="req">*</span>
          </p>
          <?php endif ?>
        </label>
      </fieldset>
      <fieldset class="page-add__group custom-form__group">
        <legend class="page-add__small-title custom-form__title">Фотография товара <span class="req">*</span></legend>
        <ul class="add-list">
          <li class="add-list__item add-list__item--add" <?= isset($productChange['image']) ? 'hidden' : '' ?>>
            <input type="file" name="product-photo" id="product-photo" hidden required accept=".jpg, .jpeg, .png">
            <label for="product-photo">Добавить фотографию</label>
          </li>
          <?php if (isset($_GET['change-id'])): ?>
            <li class="add-list__item add-list__item--active">
              <img class="product-img" src="<?= $productChange['image'] ?>" alt="<?= $productChange['name'] ?>">
            </li>
          <?php endif ?>
        </ul>
      </fieldset>
      <fieldset class="page-add__group custom-form__group">
        <legend class="page-add__small-title custom-form__title">Раздел <span class="req">*</span></legend>
        <div class="page-add__select">
          <select name="category[]" class="custom-form__select" multiple="multiple" required>
            <option hidden="">Название раздела</option>
            <?php foreach ($categories as $id => $category): ?>
              <option value="<?= $category['id'] ?>" <?= isset($productChange['category'][$category['id']]) ? 'selected' : '' ?>><?= $category['name'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <?php foreach ($promos as $id => $promo): ?>
          <input 
            type="checkbox" 
            name="promo[<?= $promo['name'] ?>]" 
            id="<?= $promo['name'] ?>" 
            class="custom-form__checkbox" 
            value="<?= $promo['id'] ?>" 
            <?= isset($productChange['promo'][$promo['id']]) ? 'checked' : '' ?>
          >
          <label for="<?= $promo['name'] ?>" class="custom-form__checkbox-label"><?= $promo['description'] ?></label>
        <?php endforeach ?>
      </fieldset>
      <?php if (isset($_GET['change-id'])): ?>
        <input type="hidden" name="id" value="<?= (int)$_GET['change-id'] ?>">
      <?php endif ?>
      <button class="button" type="submit"><?= isset($_GET['change-id']) ? 'Изменить' : 'Добавить' ?> товар</button>
    </form>
    <section class="shop-page__popup-end page-add__popup-end" hidden="">
      <div class="shop-page__wrapper shop-page__wrapper--popup-end">
        <h2 class="h h--1 h--icon shop-page__end-title">Товар успешно <?= isset($_GET['change-id']) ? 'изменён' : 'добавлен' ?></h2>
        <a href="/admin/?category=products" class="button">Продолжить</a>
      </div>
    </section>
  </main>
<?php } ?>