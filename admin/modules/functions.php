<?php

/**
 * Получение данных групп в которых состоит пользователь из БД
 * @param string $id индификационный номер пользователя
 * @return array Массив данных групп пользователя
 */

function getUserGroupInDB(string $id): array
{
    $id = (int)$id;

    $sql = "SELECT g.*, gu.user_id FROM `groups` AS g
            LEFT JOIN `group_user` AS gu ON gu.group_id = g.id
            LEFT JOIN `users` AS u ON u.id = gu.user_id
            WHERE u.id = '$id'";

    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой";
        exit;
    }
    
      $array = [];
      while ($row = $result->fetch_assoc()) {
          $array[$row['id']] = $row;
      }
      
      return $array;
}

/**
 * Авторизация пользователя по логину и паролю
 * @param string $login Логин пользователя
 * @param string $password Пароль пользователя
 * @return bool При успешной авторизации возвращает true 
 */

function authByLoginAndPass(string $login, string $password): bool
{
    $login = connectDb()->real_escape_string($login);
    $password = connectDb()->real_escape_string($password); 
    
    $sql = "SELECT * FROM users WHERE email = '{$login}'";

    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой";
        exit;
    }

    $authUser = $result->fetch_assoc();

    if ($result->num_rows !== 0 && password_verify($password, $authUser['password'])) {
        $_SESSION['user'] = $authUser;
        $_SESSION['groups'] = getUserGroupInDB($authUser['id']);
        $_SESSION['isAuth'] = true;
        return true;  

    } else {
        $_SESSION['isAuth'] = false;
        $_SESSION['inputLogin'] = $login;
        $_SESSION['inputPassword'] = $password;
        return false;
    }
}

/**
 * Получение доступных для просмотра страниц админ панели из БД
 * @param $data Массив с группами доступа пользователя или строка c ID пункта меню
 * @return array Массив данных с доступными пользователю страницами
 */

function getAccessPagesByGroupID($data): array
{
    if (is_array($data)) {
        $sqlGroups = [];

        foreach ($data as $group) {
            $sqlGroups[] = "mag.group_id = " . connectDb()->real_escape_string($group['id']);
        }
    
        // Добавлям страницы с доступом для всех
        $sqlGroups[] = 'mag.group_id IS NULL';
    
        $sqlGroups = implode(" or ", $sqlGroups);
    } else {
        $sqlGroups = 'ma.id = ' . $data;
    }
  
    $sql = "SELECT * FROM menu_admin AS ma
            LEFT JOIN menu_admin_group AS mag ON mag.menu_admin_id = ma.id
            WHERE $sqlGroups";
    
    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой";
        exit;
    }
    
    $array = [];
    while ($row = $result->fetch_assoc()) {
        $array[$row['id']] = $row;
    }
    
    return $array;
}

/**
 * Получение страницы админ панели
 * @param array $menu
 * @return string название страницы + расширение
 */

function getAdminPage(array $menu): string
{
    // Проверяем авторизацию
    if (empty($_SESSION['isAuth'])) {
       return 'authorization.php'; 
    }

    // Проверяем страницу нахождения пользователя
    if (!empty($_GET['category'])) {
        $page = htmlspecialchars($_GET['category']);
        // Получаем доступные для просмотра страницы
        foreach ($menu as $item) {
            if ($item['name'] === $page) {
                return $page . '.php';
            }
        }
        return 'error.php';
    }

    return 'main.php'; 
}

/**
 * Получение закозов из БД
 * @param string $startItem Номер начального элемента для вывода строк
 * @param string $limit Кол-во запрашиваемых строк
 * @return array Массив категорий
 */

function getOrdersInDB(string $startItem, string $limit = '10'): array
{
    $sql = "SELECT o.*, p.price FROM orders AS o
            LEFT join products AS p ON p.id = o.product_id
            ORDER BY o.processed, o.created_at DESC
            LIMIT $startItem, $limit";

    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой - orders";
        exit;
    }

    $array = [];
    while ($row = $result->fetch_assoc()) {
        $array[$row['id']] = $row;
    }
    
    return $array;
}

/**
 * Получение товаров из БД
 * @param string $startItem Номер начального элемента для вывода строк
 * @param string $limit Кол-во запрашиваемых строк
 * @return array Массив товаров по заданым параметрам
 */

function getProductsInDB(string $startItem, string $limit = '10'): array
{
    $category = getCategoryByProductInDB();
    $promo = getPromoByProductInDB();

    $sql = "SELECT * FROM `products`
            WHERE `active` = '1' ORDER BY `id` DESC LIMIT $startItem, $limit";
    
    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой - products";
        exit;
    }

    $array = [];
    while ($row = $result->fetch_assoc()) {
        $array[$row['id']] = $row;
        $array[$row['id']]['category'] = $category[$row['id']]['category'] ;
        $array[$row['id']]['promo'] = $promo[$row['id']]['promo'];
    }
    
    return $array;
}

/**
 * Получение количество строк таблиц в БД (общие количество заказов/товаров и тд)
 * @param string $table Таблица БД для запроса кол-во строк
 * @param string $where Уточняющий запрос, для получение более точных данных
 * @return int Количество строк в заданной таблице
 */

function getCountRowTableInDB(string $table, string $where = ''): int
{
    if ($where) {
        $where = " WHERE $where";
    }

    $sql = "SELECT COUNT(*) AS count FROM `$table`$where";
   
    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой 7";
        exit;
    }

    return (int)$result->fetch_object()->count;
}

/**
 * Получение всех категорий по товарам
 * @return array Массив товаров
 */

function getCategoryByProductInDB(): array
{
    $sql = "SELECT p.id, GROUP_CONCAT(c.name SEPARATOR ', ') AS category FROM products AS p
            LEFT JOIN category_product AS cp ON cp.product_id = p.id
            LEFT JOIN categories AS c ON c.id = cp.category_id
            GROUP BY p.id";

    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой";
        exit;
    }

    $array = [];
    while ($row = $result->fetch_assoc()) {
        $array[$row['id']] = $row;
    }
    
    return $array;
}

/**
 * Получение всех акций по товарам
 * @return array Массив акций
 */

function getPromoByProductInDB(): array
{
    $sql = "SELECT p.id, GROUP_CONCAT(pro.name SEPARATOR ', ') AS promo FROM products AS p
            LEFT JOIN promotion_product AS pp ON pp.product_id = p.id
            LEFT JOIN promotions AS pro ON pro.id = pp.promotion_id
            GROUP BY p.id";

    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой";
        exit;
    }

    $array = [];
    while ($row = $result->fetch_assoc()) {
        $array[$row['id']] = $row;
    }
    
    return $array;
}

/**
 * Функция проверки файлов на соотвествие типу
 * @param string $file Проверяемый файл
 * @param array $types Типы файлов
 * @return bool True - если соответсвует, False - Если нет
 */

function checkTypeFile(string $file, array $types): bool
{
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $detectedType = finfo_file($fileInfo, $file);

    finfo_close($fileInfo);

    return in_array($detectedType, $types);
}

/**
 * Функция валидации файла изображние
 * @param $fileImg Массив данных загруженного на сервер файла
 * @return bool При успешном прохождение валидации true
 */

function validateImgFile($fileImg): bool
{
    if ($fileImg === true) {
        return true;
    }
    // Максимальный размер файла
    $maxFileSize = 5242880;

    // Разрешенные типы файлов для загрузки
    $checkedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

    if ($fileImg['error'] !== 0) {
        return false;
    }

    if (filesize($fileImg['tmp_name']) > $maxFileSize) {
        return false;
    }

    if (!checkTypeFile($fileImg['tmp_name'], $checkedTypes)) {
        return false;
    }

    return true;
}

/**
 * Получение информации по товару из БД
 * @return array Массив товаров
 */

function getProductInDB(string $id): array
{
    $id = (int)$id;

    $category = getDataByProductID($id, 'category_product');
    $promo = getDataByProductID($id, 'promotion_product');
    
    $sql = "SELECT * FROM products WHERE id = $id";
    
    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой - product";
        exit;
    }

    $array = $result->fetch_assoc();

    if (!empty($category)) {
        $array['category'] = $category;  
    }

    if (!empty($promo)) {
        $array['promo'] = $promo;  
    }
    
    return $array;
}

/**
 * Получение дополнительных данных по ID товара из других таблиц
 * @param string $id Индификатор товара
 * @param string $table Таблица для поиска
 * @return array Массив дополнительных данных товара
 */

function getDataByProductID(string $id, string $table): array
{
    $id = (int)$id;

    $column = explode('_', $table)[0] . '_id';

    $sql = "SELECT $column FROM `$table` WHERE product_id = $id";
    
    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой 10";
        exit;
    }

    $array = [];
    while ($row = $result->fetch_assoc()) {
        $array[$row[$column]] = $row;
    }
    
    return $array;
}

/**
 * Удаление данных по ID товара из таблиц
 * @param string $id Индификатор товара
 * @param string $table Таблица для поиска
 */

function deleteDataToBD(string $id, string $table)
{
    $id = (int)$id;

    $sql = "DELETE FROM `$table` WHERE product_id = '$id'";
    
    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой - delete";
        exit;
    }
}





