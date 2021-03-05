<?php 
require($_SERVER['DOCUMENT_ROOT'] . '/modules/base/index.php');
require($_SERVER['DOCUMENT_ROOT'] . '/modules/shop/index.php');
// Шапка сайта
require($_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'); 

// Магазин
require($_SERVER['DOCUMENT_ROOT'] . '/templates/shop.php'); 
require($_SERVER['DOCUMENT_ROOT'] . '/templates/order.php'); 

// Подвал сайта
require($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php');