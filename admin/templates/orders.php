<main class="page-order">
  <h1 class="h h--1">Список заказов</h1>
  <ul class="page-order__list">
    <?php foreach ($ordersShop as $key => $order): ?>
    <li class="order-item page-order__item">
      <div class="order-item__wrapper">
        <div class="order-item__group order-item__group--id">
          <span class="order-item__title">Номер заказа</span>
          <span class="order-item__info order-item__info--id"><?= $order['id'] ?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Сумма заказа</span>
          <?= $order['total_cost'] ?> руб.
        </div>
        <button class="order-item__toggle"></button>
      </div>
      <div class="order-item__wrapper">
        <div class="order-item__group order-item__group--margin">
          <span class="order-item__title">Заказчик</span>
          <span class="order-item__info"><?= $order['surname'] . ' ' . $order['name'] . ' ' . $order['third_name'] ?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Номер телефона</span>
          <span class="order-item__info"><?= $order['phone'] ?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Способ доставки</span>
          <span class="order-item__info"><?= $order['delivery'] === 'dev-no' ? 'Самовывоз' : 'Курьерская' ?></span>
        </div>
        <div class="order-item__group">
          <span class="order-item__title">Способ оплаты</span>
          <span class="order-item__info"><?= $order['pay'] === 'card' ? 'Банковской картой' : 'Наличными' ?></span>
        </div>
        <div class="order-item__group order-item__group--status">
          <span class="order-item__title">Статус заказа</span>
          <span class="order-item__info order-item__info--<?= $order['processed'] ? 'yes' : 'no' ?>"><?= $order['processed'] ? 'Выполнено' : 'Не выполнено' ?></span>
          <button class="order-item__btn">Изменить</button>
        </div>
      </div>
      <?php if ($order['delivery'] === 'dev-yes'): ?>
        <div class="order-item__wrapper">
          <div class="order-item__group">
            <span class="order-item__title">Адрес доставки</span>
            <span class="order-item__info">г. <?= $order['city'] ?>, ул. <?= $order['street'] ?>, д.<?= $order['home'] ?>, кв. <?= $order['aprt'] ?></span>
          </div>
        </div>
      <?php endif ?>
      <div class="order-item__wrapper">
        <div class="order-item__group">
          <span class="order-item__title">Комментарий к заказу</span>
          <span class="order-item__info"
            ><?= $order['comment'] ?></span
          >
        </div>
      </div>
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
