<?php
/**
 * Добавление/Изменение товара в админ-панели
 */

require($_SERVER['DOCUMENT_ROOT'] . '/admin/modules/functions.php');
require($_SERVER['DOCUMENT_ROOT'] . '/modules/base/functions.php');

// Путь до папки сохранения загруженных изображений
$uploadPath = '/img/products/';

// Определяем наличие файлов
$file = $_FILES['product-photo'] ?? false;
// При изменение данных товара определяем передана ли новое изображение
if (isset($_POST['id'])) {
    $file = $_FILES['product-photo'] ?? true;
}

// Валидация формы на стороне сервера
if (!empty($_POST['product-name']) && !empty($_POST['product-price']) && !empty($file) && !empty($_POST['category'])) {
    if (validateImgFile($file)) {
        if (isset($_POST['id'])) {
            $postProduct['id'] = $_POST['id'];

            deleteDataToBD($_POST['id'], 'category_product');
            deleteDataToBD($_POST['id'], 'promotion_product');
        }

        $postProduct['name'] = $_POST['product-name'];
        $postProduct['price'] = $_POST['product-price'];

        if (is_array($file)) {
            $postProduct['image'] = $uploadPath . $file['name'];
            move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $postProduct['image']);   
        }
        
        sendDataToDB($postProduct, 'products', isset($_POST['id']) ? true : false);

        // Получаем ID созданого товара
        $productID = isset($_POST['id']) ? $_POST['id'] : connectDb()->insert_id;
        
        // Добавляем товар в таблицы БД
        if (!empty($_POST['category'])) {
            foreach ($_POST['category'] as $key => $category) {
                $productCatagery['category_id'] = $category;
                $productCatagery['product_id'] = $productID;
                sendDataToDB($productCatagery, 'category_product');
            }
        }

        if (!empty($_POST['promo'])) {
            foreach ($_POST['promo'] as $key => $promo) {
                $productPromo['promotion_id'] = $promo;
                $productPromo['product_id'] = $productID;
                sendDataToDB($productPromo, 'promotion_product'); 
            }
        }
       
        echo 'Успешная отправка!';
    }
}


