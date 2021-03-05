<?php
require($_SERVER['DOCUMENT_ROOT'] . '/admin/modules/functions.php');

// Обработка выхода из авторизации
if (isset($_GET['exit'])) {
    unset($_SESSION['isAuth']);

    setcookie(session_name(), session_id(), time() - 60 * 60 * 24, '/');

    session_destroy();

    header('Location: /admin/');
    die();
}

// Конфигурация Админ-панели
$quantityProducts = 10; // Кол-во товаров на странице
$quantityOrders = 10; // Кол-во заказов на странице

$inputLogin = '';
$inputPassword = '';

$ordersShop = [];

// Получаем список доступных страниц для пользователя
$menuFooter = getMenuInDB('shop');

if (isset($_POST['submit'])) {
    if (!empty($_POST['login']) && !empty($_POST['password'])) {
        if (!authByLoginAndPass($_POST['login'], $_POST['password'])) {
            $inputLogin = htmlspecialchars($_POST['login']);
            $inputPassword = htmlspecialchars($_POST['password']);
        }
    } else {
        $inputLogin = htmlspecialchars($_POST['login']);
        $inputPassword = htmlspecialchars($_POST['password']);
    }
}

// Проверяем авторизацию
if (!empty($_SESSION['isAuth'])) {

    // Получаем данные выбранной страницы
    if (isset($_GET['category']) && $_GET['category'] === 'orders') {
        $countOrders = getCountRowTableInDB('orders');
        $quantityPages = ceil($countOrders / $quantityOrders);
        $pageStartItem = getStartItemOnPage($countOrders, $quantityOrders);

        $ordersShop = getOrdersInDB($pageStartItem, $quantityOrders);  
    }

    if (isset($_GET['category']) && $_GET['category'] === 'products') {
        if (!isset($_GET['add'])) {
            $countProducts = getCountRowTableInDB('products', "`active` = '1'");
            $quantityPages = ceil($countProducts / $quantityProducts);
            $pageStartItem = getStartItemOnPage($countProducts, $quantityProducts);

            $productsShop = getProductsInDB($pageStartItem, $quantityProducts);
        } else {
            $categories = getAllCategoriesInDB();
            $promos = getAllPromotionsInDB();

            if (!empty($_GET['change-id'])) {
                $productChange = getProductInDB($_GET['change-id'], $categories, $promos);
            }
        } 
    } 
}
// Получаем доступные пункты меню
$menuHeader = !empty($_SESSION['isAuth']) ? getAccessPagesByGroupID($_SESSION['groups'] ?? []) : getAccessPagesByGroupID(1);
