<?php

/**
 * Модуль изменения статуса товара или заказа в БД
 */

require($_SERVER['DOCUMENT_ROOT'] . '/modules/base/functions.php');

if (!empty($_POST['id'])) {

    $tableSql = '';
    $fieldSql = '';
    $valueSql = '';
    $id = (int)$_POST['id'];
    // Заказ товара
    if (isset($_POST['processed'])) {
        $tableSql = 'orders';
        $fieldSql = 'processed';
        $valueSql = connectDb()->real_escape_string($_POST['processed']);
    }
   
    // Товар
    if (isset($_POST['active'])) {
        $tableSql = 'products';
        $fieldSql = 'active';
        $valueSql = connectDb()->real_escape_string($_POST['active']);
    }
    
    $sql = "UPDATE `$tableSql` SET `$fieldSql` = '$valueSql' WHERE  `id` = '$id'";

    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой";
        exit;
    }

    echo "Успешно!";
}