<?php 

require($_SERVER['DOCUMENT_ROOT'] . '/modules/shop/functions.php');

$menuFooter = $menuHeader = getMenuInDB('shop');

$categories = getAllCategoriesInDB();
$promotions = getAllPromotionsInDB();

$productFilters = [];

// Дополнительные параметры запроса SQL
$leftJoinSql = [];
$whereSql = [];
$orderSql = '';

// Обрабатываем GET данные
if (!empty($_GET['category'])) {
    $leftJoinSql[] = 'category_product AS cp ON cp.product_id = p.id';
    $whereSql[] = 'cp.category_id = ' . connectDb()->real_escape_string($_GET['category']); 
}

if (!empty($_GET['filter'])) {
    foreach ($_GET['filter'] as $filter => $value) {
        if ($filter === 'price-min') {
            $whereSql[] = 'p.price >= ' . connectDb()->real_escape_string($value);
        }
        
        if ($filter === 'price-max') {
            $whereSql[] = 'p.price <= ' . connectDb()->real_escape_string($value);
        }

        if (!empty($promotions[htmlspecialchars($filter)]['name'])) {
            $productFilters[] = 'pp.promotion_id = ' . connectDb()->real_escape_string($promotions[htmlspecialchars($filter)]['id']);
        }
    }
    
    if (!empty($productFilters)) {
        $leftJoinSql[] = 'promotion_product AS pp ON pp.product_id = p.id';
        $stringProductFilters ='(' . implode(" OR ", $productFilters) . ')';
        $whereSql[] = $stringProductFilters; 
    } 
}

if (!empty($_GET['sort'])) {
    $orderSql = 'ORDER BY ' . connectDb()->real_escape_string($_GET['sort']) . ' ' . connectDb()->real_escape_string($_GET['order']) ?? 'ASC';
}

$countProducts = getCountFilterProductsInDB($leftJoinSql, $whereSql);

// Определение максимальной и минимальной цены
$minAndMaxPrice = getMinMaxPriceProductsInDB();
$minPrice = (int)$minAndMaxPrice['min'];
$maxPrice = (int)$minAndMaxPrice['max'];

// Определение товаров по номеру страницы
$quantityPages = ceil($countProducts / $quantityProductsPage);
$pageStartItem = getStartItemOnPage($countProducts, $quantityProductsPage);
$products = getProductsInDB($leftJoinSql, $whereSql, $orderSql, $pageStartItem, $quantityProductsPage);

!empty($products) ? $products = addPromotionsInProducts($products) : $countProducts = 0;


