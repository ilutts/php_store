<?php

/**
 * Получение цены товара из БД для корректной определение стоимости
 * @param string $id Индификатор товара в БД
 * @return int цена товара
 */

function getProductPriceInDB(string $id): int
{
    $id = (int)$id;
  
    $sql = "SELECT price FROM products WHERE id = $id";
            

    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой 7";
        exit;
    }

    return (int)$result->fetch_object()->price;
}

/** 
* Проверка данных из формы (Валидация)
* @param array $post Массив данных с формы заказа товара
* @param array $post Массив товаров
* @return string Сообщение о статусе отправки данных
*/

function checkOrderData(): string
{
    if (!empty($_POST['surname']) && !empty($_POST['name']) && !empty($_POST['phone']) && !empty($_POST['email'])) {
        
        if ($_POST['delivery'] === 'dev-yes' && 
            (empty($_POST['city']) || empty($_POST['street']) || empty($_POST['home']) || empty($_POST['aprt']))
        ) {
            return 'Ошибка валидации!';
        }
        $post = $_POST;
        
        include $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        
        $post['price'] = getProductPriceInDB((int)$post['product_id']);
        $post['del_price'] = 0;
        
        if ($post['price'] < $minSumOrderFreeShip) {
            $post['del_price'] = $courierDeliveryPrice;
        }

        $post['total_cost'] = $post['price'] + $post['del_price'];

        return sendDataToDB($post, 'orders') ? 'Успешная отправка' : 'Ошибка отправки!';
    } 
    
    return 'Ошибка валидации!';
}