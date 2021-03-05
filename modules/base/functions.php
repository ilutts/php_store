<?php 

/**
 * Соединение с базай данных
 * @return object С данными по подключению к базе 
 */

function connectDb(): object 
{
    static $mysqli = null;
    
    if (null === $mysqli) {
        // Подключаем данные для соединения с БД
        include $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        
        // Создаем подключение к БД
        $mysqli = new mysqli($host, $user, $password, $database);
        
        if ($mysqli->connect_errno) {
            echo "Ошибка: Не удалась создать соединение с базой MySQL";   
            exit;
        }
        
        $mysqli->set_charset("utf8mb4");
    }

    return $mysqli;
}

/**
 * Получение данных меню из БД
 * @return array Массив данных меню сайта
 */

function getMenuInDB(string $menu): array
{
    $menu = connectDb()->real_escape_string($menu);
   
    $sql = "SELECT * FROM menu_" . $menu;

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
 * Функция определения корректного URL
 * @param string $url URL для преобразования
 * @return bool true при корректности
 */

function isCurrentUrl(string $url): bool
{
    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $urlQuery = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
    
    return $urlQuery ? $urlPath . '?' . $urlQuery == $url : $urlPath == $url;
}

/**
 * Определяем статус кнопки поганиции для добавление ссылки
 * @param int $page Номер страницы
 * @return string Пустая строка если страница активна
 */

function getStatusPage(int $page): string
{
    if ((!empty($_GET['page']) && (int)$_GET['page'] !== $page) || (empty($_GET['page']) && $page !== 1)) {
       return 'href="' . getQueryUrl('page', $page) . '"';
    }

    return '';
}

/**
 * Получение адреса ссылки
 * @param string $type Тип ссылки, к примеру 'page'
 * @param int $id Номер элемента
 * @return string Ссылка для перехода
 */

function getQueryUrl(string $type, int $id): string
{
   $urlPage = '?' . $type . '=';
   // Получаем строку запроса
   $urlQuery = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

   if ($urlQuery) {
       
       if (!empty($_GET[$type])) {
           $arrayQuery = [];

           parse_str($urlQuery, $arrayQuery);
           
           $arrayQuery[$type] = $id;
           return '?' . http_build_query($arrayQuery);
       }
       $urlPage = '&' . $type . '=';
   }

   return $_SERVER['REQUEST_URI'] . $urlPage . $id;
}

/**
 * Отправка заказа клиента в базу данных
 * @param array $post Массив данных с формы заказа товара
 * @param string $table Таблица базы данных для внесения новой информации
 * @param bool $isUpdate Требуется обновление данных или простая вставка новых
 * @return bool Сообщение о статусе отправки данных
 */

function sendDataToDB(array $post, string $table, bool $isUpdate = false): bool
{
    $columns = [];
    $row = [];
    
    $updateSql = '';
    $updateArray = [];

    foreach ($post as $key => $value) {
        // Формируем массивы для запроса SQL + Защищаем от SQL Атак
        $columns[] = "`" . connectDb()->real_escape_string($key) . "`";
        $row[] = "'" . connectDb()->real_escape_string($value) . "'";
        if ($isUpdate) {
            $updateArray[] = "`" . connectDb()->real_escape_string($key) . "`" . ' = ' . "'" . connectDb()->real_escape_string($value) . "'";
        }
    }

    $columns = implode(",", $columns);
    $row = implode(",", $row);
    if ($isUpdate) {
        $updateSql = 'ON DUPLICATE KEY UPDATE ' . implode(",", $updateArray);
    } 

    $sql = "INSERT INTO `$table` ($columns)
            VALUES ($row)
            $updateSql";

    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой - insert";
        return false;
    }

    return true;
}

/**
 * Получение списка категорий из БД
 * @return array Массив категорий
 */

function getAllCategoriesInDB(): array
{
    $sql = "SELECT * FROM categories";

    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой 6";
        exit;
    }
    
    $array = [];
    while ($row = $result->fetch_assoc()) {
        $array[$row['id']] = $row;
    }
    
    return $array;
}

/**
 * Получение списка акций товаров
 * @return array Массив акция
 */

function getAllPromotionsInDB(): array
{
    $sql = "SELECT * FROM promotions";

    if (!$result = connectDb()->query($sql)) {
        echo "Запрос в базу MySQL произашел с ошибкой 2";
        exit;
    }

    $array = [];
    while ($row = $result->fetch_assoc()) {
        $array[$row['name']] = $row;
    }
    
    return $array;
}

/**
 * Получение начального элемента страницы для формирование страницы
 * @param int $allCount Общие кол-во элементов
 * @param int $quantityShow Кол-во элементов для вывода на странице
 * @return int Номер начальноо элемента
 */

function getStartItemOnPage (int $allCount, int $quantityShow): int
{
    $startItem = 0;
    $finishItem = $allCount > $quantityShow ? $quantityShow : $allCount;
    
    if (!empty($_GET['page']) && (int)$_GET['page'] > 1) {
        $numberPage = (int)$_GET['page'];
        $finishItem = $finishItem * $numberPage;
        $startItem = $finishItem - $quantityShow;
        if ($startItem < 0) {
            $startItem = $quantityShow + 1;
        }
    }

    return $startItem;
}

/**
 * Получение название страницы для указание в заголовке
 * @param array $menu Массив с меню для получения наименования
 * @return string Название страницы
 */

function getTitlePage(array $menu): string 
{
    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $urlQuery = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

    $url = $urlQuery ? $urlPath . '?'. $urlQuery : $urlPath;

    foreach ($menu as $item) {
       if ($item['path'] === $url) {
           return ' - ' . $item['title'];
       }
    }
    
    return '';
}