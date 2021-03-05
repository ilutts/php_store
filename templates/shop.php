
<main class="shop-page">
  <header class="intro">
    <div class="intro__wrapper">
      <h1 class=" intro__title">COATS</h1>
      <p class="intro__info">Collection 2018</p>
    </div>
  </header>

  <section class="shop container">   
    <section class="shop__filter filter">
      <form class="shop__form" method="GET" action="<?= parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?>">
        <div class="filter__wrapper">
          <b class="filter__title">Категории</b>
          <ul class="filter__list">
            <li>
              <a class="filter__list-item <?= empty($_GET['category']) ? 'active' : '' ?>" href="/">
                Все
              </a>
            </li>
            <?php foreach ($categories as $category): ?>
              <li>
                <a class="filter__list-item <?= getActiveCategory('category', $category['id']) ?>" href="?category=<?= $category['id'] ?>">
                  <?= $category['name'] ?>
                </a>
              </li>
            <?php endforeach ?>
          </ul>
        </div>
        <?php if (!empty($_GET['category'])): ?>
          <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
        <?php endif ?>
        <?php if (!empty($_GET['sort'])): ?>
          <input type="hidden" name="sort" value="<?= htmlspecialchars($_GET['sort']) ?>"> 
        <?php endif ?>
        <?php if (!empty($_GET['order'])): ?>
          <input type="hidden" name="order" value="<?= htmlspecialchars($_GET['order']) ?>"> 
        <?php endif ?>
        <div class="filter__wrapper">
          <b class="filter__title">Фильтры</b>
          <div class="filter__range range">
            <span class="range__info">Цена</span>
            <div class="range__line" aria-label="Range Line"></div>
            <div class="range__res">
              <span class="range__res-item min-price"></span>
              <input class="range__input--min" type="hidden" name="filter[price-min]" value="<?= $minPrice ?>">
              <span class="range__res-item max-price"></span>
              <input class="range__input--max" type="hidden" name="filter[price-max]" value="<?= $maxPrice ?>">
            </div>
          </div>
        </div>

        <fieldset class="custom-form__group">
          <?php foreach ($promotions as $key => $promotion): ?>
            <input type="checkbox" name="filter[<?= $promotion['name'] ?>]" id="<?= $promotion['name'] ?>" class="custom-form__checkbox" <?= getStatusCheckbox($promotion['name'])?>>
            <label for="<?= $promotion['name'] ?>" class="custom-form__checkbox-label custom-form__info" style="display: block;"><?= $promotion['description'] ?></label>
          <?php endforeach ?>
        </fieldset>
        <button class="button" type="submit" style="width: 100%">Применить</button>
      </form>
    </section>

    <div class="shop__wrapper">
      <form class="shop__sorting" method="GET" action="/">
        <?php if (!empty($_GET['category'])): ?>
          <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
        <?php endif ?>
        <?php if (!empty($_GET['filter'])) {
          foreach ($_GET['filter'] as $filter => $value) { ?>
            <input type="hidden" name="filter[<?= htmlspecialchars($filter) ?>]" value="<?= htmlspecialchars($value) ?>">    
        <?php } }  ?>
        <div class="shop__sorting-item custom-form__select-wrapper">
          <select class="custom-form__select select-sort" name="sort">
            <option hidden value="">Сортировка</option>
            <option value="price">По цене</option>
            <option value="name">По названию</option>
          </select>
        </div>
        <div class="shop__sorting-item custom-form__select-wrapper">
          <select class="custom-form__select select-order" name="order">
            <option hidden value="">Порядок</option>
            <option value="ASC">По возрастанию</option>
            <option value="DESC">По убыванию</option>
          </select>
        </div>
        <p class="shop__sorting-res">
          Найдено <span class="res-sort"><?= $countProducts ?></span> <?= declOfNum($countProducts, ['модель', 'модели', 'моделей']) ?>
        </p>
      </form>
        <section class="shop__list">
          <?php foreach ($products as $product) {?>
            <article class="shop__item product" tabindex="<?= $product['id'] ?>">
              <div class="product__image">
                <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
              </div>
              <p class="product__name"><?= $product['name'] ?></p>
              <span class="product__price"><?= $product['price'] ?> руб.</span>
              <?php if (!empty($product['promo'])): ?>
                <span class="product__promo"><?= putProductPromotions($product) ?></span>
              <?php endif ?>
            </article>
          <?php } ?>
        </section>
        <ul class="shop__paginator paginator">
        <?php for ($i = 1; $i <= $quantityPages; $i++): ?>
            <li>
              <a class="paginator__item" <?= getStatusPage($i) ?>><?= $i ?></a>
            </li>
          <?php endfor ?>
        </ul>
  </div>
</section>