<?php

/**
 * Получение списка товаров из БД (построение запроса SQL)
 * @param array $LeftJoins Набор информации о доп. таблицах БД
 * @param array $where Набор данных для фильтрации в БД 
 * @param string $order Информация о сортировки
 * @param int $startItem Начальный элемент отбора данных
 * @param int $limit Количество данных для выгрузки из БД
 * @return array Массив товаров
 */

function getProductsInDB(array $leftJoins, array $where, string $order, int $startItem, int $limit): array
{

    // Обработка дополнительный массивов
    foreach ($leftJoins as $key => $item) {
        $leftJoins[$key] = "LEFT JOIN " . connectDb()->real_escape_string($item);
    }

    // Строка запроса дополнительный таблиц БД
    $sqlLeftJoin = implode(" ", $leftJoins);

    // Строка запроса дополнительных условий
    $sqlWhere = !empty($where) ? $sqlWhere = "p.active = '1' AND " . implode(" AND ", $where) : "p.active = '1'";
   
    $sql = "SELECT DISTINCT p.* FROM products AS p
            $sqlLeftJoin
            WHERE $sqlWhere $order LIMIT $startItem, $limit";
    
    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой 1";
        exit;
    }
    
    $array = [];
    while ($row = $result->fetch_assoc()) {
        $array[$row['id']] = $row;
    }
    
    return $array;
}

/**
 * Получение общего количество товаров после фильтрации
 * @param array $LeftJoins Набор информации о доп. таблицах БД
 * @param array $where Набор данных для фильтрации в БД 
 * @return int Количество товаров с учетом фильтрации
 */

function getCountFilterProductsInDB(array $leftJoins, array $where): int
{
    // Обработка дополнительный массивов
    foreach ($leftJoins as $key => $item) {
        $leftJoins[$key] = "LEFT JOIN " . connectDb()->real_escape_string($item);
    }

    $sqlLeftJoin = implode(" ", $leftJoins);
  
    $sqlWhere = !empty($where) ? $sqlWhere = "p.active = '1' AND " . implode(" AND ", $where) : "p.active = '1'";
    
    $sql = "SELECT COUNT(DISTINCT p.id) AS count FROM products AS p
            $sqlLeftJoin
            WHERE $sqlWhere";
    
    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой 7";
        exit;
    }

    return (int)$result->fetch_object()->count;
}

/**
 * Получение максимальной и минимальной цены товаров
 * @return array Массив с минимальной и максимальной ценой
 */

function getMinMaxPriceProductsInDB(): array
{
    $sql = "SELECT MIN(p.price) AS min, MAX(p.price) AS max FROM products AS p";
            
    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой 7";
        exit;
    }

    return $result->fetch_array(MYSQLI_ASSOC);
}


/**
 * Получение списка ID товаров с ID акциями по условию
 * @param array $products Массив товаров
 * @return array Массив ID товаров с ID акциями
 */

function getProductsWithPromotions(array $products): array
{
    foreach ($products as $key => $id) {
        $products[$key] = 'pp.product_id = ' . (int)$id;
    }

    $sqlWhere = implode(" OR ", $products);

    $sql = "SELECT pp.*, p.name FROM promotion_product AS pp
            LEFT JOIN promotions AS p ON p.id = pp.promotion_id
            WHERE $sqlWhere";

    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой 3";
        exit;
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Добавление информации по акциям в товар
 * @param array $products Массив товаров
 * @return array Массив товаров с информацией о акциях
 */

function addPromotionsInProducts(array $products): array
{
    $productsWithPromotions = getProductsWithPromotions(array_keys($products));
    foreach ($productsWithPromotions as $item) {
        $products[$item['product_id']]['promo'][$item['name']] = $item['name'];
    }

    return $products;
}

/**
 * Получение статуса чекбокса из данных фильтров
 * @param array $filters Массив фильтров
 * @param string $checkbox Название чекбокса
 * @return string Атрибут тега
 */

function getStatusCheckbox(string $checkbox): string
{
    if (!empty($_GET['filter'])) {
        if (array_key_exists($checkbox, $_GET['filter'])) {
            return 'checked';
        }
    }
   
    return '';
}

/**
 * Получение классов сттилей аукцинных продуктов
 * @param array $product Массив фильтров
 * @return string Классы стилей
 */

function putProductPromotions(array $product): string
{
    $classPromo = '';
    if (!empty($product['promo'])) {
        foreach ($product['promo'] as $promo) {
            $classPromo = $classPromo . ' ' . $promo;
        }
    }

    return $classPromo;
}

 /**
 * Определяем активную ссылку для добавления класса CSS
 * @param string $type Тип ссылки, к примеру 'page'
 * @param int $id Номер элемента
 * @return string Добавляем класс если страница активна
 */

function getActiveCategory(string $type, int $id): string
{
    if (!empty($_GET[$type]) && (int)$_GET[$type] === $id || empty($_GET[$type]) && $id === 1) {
        return 'active';
    }
    
    return '';
}

/**
 * Склонение слов по правилам русского языка
 * @param int $num Число
 * @param array $titles Массив вариантов склонения слова
 * @return string Склоненое слово
 */

function declOfNum(int $num, array $titles): string
{
    $cases = array(2, 0, 1, 1, 1, 2);

    return $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
}
