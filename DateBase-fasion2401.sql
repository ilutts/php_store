-- --------------------------------------------------------
-- Хост:                         192.168.0.77
-- Версия сервера:               8.0.19 - MySQL Community Server - GPL
-- Операционная система:         Win64
-- HeidiSQL Версия:              11.1.0.6116
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Дамп структуры для таблица fashion_shop.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы fashion_shop.categories: ~5 rows (приблизительно)
DELETE FROM `categories`;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `name`) VALUES
	(2, 'Женщины'),
	(3, 'Мужчины'),
	(4, 'Дети'),
	(5, 'Аксессуары');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Дамп структуры для таблица fashion_shop.category_product
CREATE TABLE IF NOT EXISTS `category_product` (
  `category_id` int NOT NULL,
  `product_id` int NOT NULL,
  PRIMARY KEY (`category_id`,`product_id`),
  KEY `FK_category_product_products` (`product_id`),
  CONSTRAINT `FK__categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `FK_category_product_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы fashion_shop.category_product: ~27 rows (приблизительно)
DELETE FROM `category_product`;
/*!40000 ALTER TABLE `category_product` DISABLE KEYS */;
INSERT INTO `category_product` (`category_id`, `product_id`) VALUES
	(2, 1),
	(2, 2),
	(4, 3),
	(2, 4),
	(2, 5),
	(2, 6),
	(2, 7),
	(2, 8),
	(2, 9),
	(2, 10),
	(5, 10),
	(5, 11),
	(2, 12),
	(2, 13),
	(2, 18),
	(3, 18),
	(5, 19),
	(5, 20),
	(2, 21),
	(3, 21),
	(2, 22),
	(3, 22),
	(2, 23),
	(5, 23),
	(5, 24),
	(5, 25),
	(3, 26),
	(5, 27),
	(5, 28);
/*!40000 ALTER TABLE `category_product` ENABLE KEYS */;

-- Дамп структуры для таблица fashion_shop.groups
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы fashion_shop.groups: ~2 rows (приблизительно)
DELETE FROM `groups`;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` (`id`, `name`, `description`) VALUES
	(1, 'administrator', 'может заходить в административный интерфейс, видеть список заказов и управлять товарами'),
	(2, 'operator', 'может заходить в административный интерфейс и видеть список заказов.');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;

-- Дамп структуры для таблица fashion_shop.group_user
CREATE TABLE IF NOT EXISTS `group_user` (
  `group_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`group_id`,`user_id`),
  KEY `FK_group_user_users` (`user_id`),
  CONSTRAINT `FK_group_user_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `FK_group_user_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы fashion_shop.group_user: ~3 rows (приблизительно)
DELETE FROM `group_user`;
/*!40000 ALTER TABLE `group_user` DISABLE KEYS */;
INSERT INTO `group_user` (`group_id`, `user_id`) VALUES
	(1, 1),
	(2, 1),
	(2, 2);
/*!40000 ALTER TABLE `group_user` ENABLE KEYS */;

-- Дамп структуры для таблица fashion_shop.menu_admin
CREATE TABLE IF NOT EXISTS `menu_admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы fashion_shop.menu_admin: ~4 rows (приблизительно)
DELETE FROM `menu_admin`;
/*!40000 ALTER TABLE `menu_admin` DISABLE KEYS */;
INSERT INTO `menu_admin` (`id`, `title`, `name`, `path`) VALUES
	(1, 'Главная', 'main', '/admin/'),
	(2, 'Товары', 'products', '/admin/?category=products'),
	(3, 'Заказы', 'orders', '/admin/?category=orders'),
	(4, 'Выйти', 'exit', '/admin/?exit');
/*!40000 ALTER TABLE `menu_admin` ENABLE KEYS */;

-- Дамп структуры для таблица fashion_shop.menu_admin_group
CREATE TABLE IF NOT EXISTS `menu_admin_group` (
  `menu_admin_id` int NOT NULL,
  `group_id` int NOT NULL,
  PRIMARY KEY (`menu_admin_id`,`group_id`),
  KEY `FK__groups` (`group_id`),
  CONSTRAINT `FK__groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `FK__menu_admin` FOREIGN KEY (`menu_admin_id`) REFERENCES `menu_admin` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы fashion_shop.menu_admin_group: ~3 rows (приблизительно)
DELETE FROM `menu_admin_group`;
/*!40000 ALTER TABLE `menu_admin_group` DISABLE KEYS */;
INSERT INTO `menu_admin_group` (`menu_admin_id`, `group_id`) VALUES
	(2, 1),
	(3, 1),
	(3, 2);
/*!40000 ALTER TABLE `menu_admin_group` ENABLE KEYS */;

-- Дамп структуры для таблица fashion_shop.menu_shop
CREATE TABLE IF NOT EXISTS `menu_shop` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `path` text NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы fashion_shop.menu_shop: ~4 rows (приблизительно)
DELETE FROM `menu_shop`;
/*!40000 ALTER TABLE `menu_shop` DISABLE KEYS */;
INSERT INTO `menu_shop` (`id`, `title`, `path`, `name`) VALUES
	(1, 'Главная', '/', 'main'),
	(2, 'Новинки', '/?filter[new]', 'new'),
	(3, 'Sale', '/?filter[sale]', 'sale'),
	(4, 'Доставка', 'delivery.php', 'delivery');
/*!40000 ALTER TABLE `menu_shop` ENABLE KEYS */;

-- Дамп структуры для таблица fashion_shop.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `surname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `third_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `delivery` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `home` varchar(255) DEFAULT NULL,
  `aprt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `pay` varchar(255) NOT NULL,
  `comment` text,
  `created_at` datetime NOT NULL,
  `processed` tinyint NOT NULL DEFAULT '0',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `del_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_orders_products` (`product_id`),
  CONSTRAINT `FK_orders_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы fashion_shop.orders: ~19 rows (приблизительно)
DELETE FROM `orders`;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` (`id`, `product_id`, `surname`, `name`, `third_name`, `phone`, `email`, `delivery`, `city`, `street`, `home`, `aprt`, `pay`, `comment`, `created_at`, `processed`, `price`, `del_price`, `total_cost`) VALUES
	(1, 7, 'iv', 'iv', 'iv', '77777', '1@1', 'del-no', NULL, NULL, NULL, NULL, 'bank', 'ffff', '2021-01-12 19:13:21', 1, 2000.00, 280.00, 0.00),
	(2, 7, 'iv1', 'iv1', 'iv1', '77777', '1@1', 'del-no', NULL, NULL, NULL, NULL, 'bank', 'ffff', '2021-01-12 19:21:01', 0, 2999.00, 280.00, 0.00),
	(3, 4, 'Лутцев', 'Иван', '', '666666777', '6@6', 'dev-no', '', '', '', '', 'card', 'testovich', '2021-01-12 19:41:25', 1, 3590.00, 0.00, 3590.00),
	(4, 10, 'Иванов', 'Иван', 'Иванович', '777888999', 'ivan@ivan.ru', 'dev-yes', 'Тюмень', 'Ленина', '2а', '25', 'cash', 'Жду заказ :)', '2021-01-12 19:43:06', 0, 1599.00, 0.00, 1599.00),
	(5, 2, 'Абдула', 'Касим', 'Вагитович', '4444', '6@5', 'dev-yes', 'Тест', 'Удачная', '7', '25', 'card', 'Проверка', '2021-01-12 19:47:08', 0, 1850.00, 0.00, 1850.00),
	(6, 2, 'Рахат', 'Лукум', '', '666111222', 'rah@luk.com', 'dev-yes', 'stambul', 'lenina', '2', '25', 'card', 'Having order test', '2021-01-12 19:53:18', 0, 1850.00, 280.00, 2130.00),
	(7, 1, 'Маёр', 'Начальника', '', '666677766', '45@34', 'dev-no', '', '', '', '', 'cash', '', '2021-01-12 20:32:31', 0, 2000.00, 0.00, 2000.00),
	(8, 5, 'Петров', 'Актер', 'Актёрович', '7922222455', 'actor@actor.ru', 'dev-yes', 'Москва', 'Кремль', '6', '25', 'card', 'Очень жду', '2021-01-15 11:44:29', 0, 2999.00, 0.00, 2999.00),
	(9, 18, 'Сидоров', 'Сидор', 'Сидорович', '7922244455', 'sidor@sidor.ru', 'dev-no', '', '', '', '', 'card', '', '2021-01-15 16:00:54', 0, 1550.00, 280.00, 1830.00),
	(10, 23, 'Кипелов', 'Кипел', 'Кипелович', '888888888', 'kipel@kipelov.ru', 'dev-yes', 'Ноябрьск', 'Северная', '2', '3', 'card', '', '2021-01-17 11:16:02', 0, 2150.00, 0.00, 2150.00),
	(11, 2, 'Иванов', 'Иван', '', '7777766666', '9@9.ru', 'dev-yes', 'Тюмень', 'Ленина', '2', '25', 'card', '', '2021-01-20 08:22:16', 0, 1850.00, 280.00, 2130.00),
	(12, 3, 'Тронин', 'Андрей', '', '7888333555', '23@23.ru', 'dev-no', '', '', '', '', 'card', '', '2021-01-20 08:39:32', 0, 2999.00, 0.00, 2999.00),
	(13, 5, 'Сидоров', 'Сидор', '', '793336666', 'as@as.ru', 'dev-no', '', '', '', '', 'card', '', '2021-01-20 08:40:27', 0, 2999.00, 0.00, 2999.00),
	(14, 5, 'Сидоров', 'Сидор', '', '793336666', 'as@as.ru', 'dev-no', '', '', '', '', 'card', '', '2021-01-20 08:40:27', 0, 2999.00, 0.00, 2999.00),
	(15, 8, 'Тестовый', 'Тест', '', '777888555', 'test@test,com', 'dev-no', '', '', '', '', 'cash', '', '2021-01-20 08:52:37', 0, 4667.00, 0.00, 4667.00),
	(16, 1, 'Тестовый2', 'Тест2', '', '66633377755', 'ac@ac.ru', 'dev-no', '', '', '', '', 'card', '', '2021-01-20 08:53:18', 0, 2000.00, 0.00, 2000.00),
	(17, 1, 'Тестовый2', 'Тест2', '', '66633377755', 'ac@ac.ru', 'dev-no', '', '', '', '', 'card', '', '2021-01-20 08:53:18', 0, 2000.00, 0.00, 2000.00),
	(18, 2, 'Иванов', 'Иван', '', '89993333', '23@23.ru', 'dev-no', '', '', '', '', 'card', '', '2021-01-20 08:55:35', 0, 1850.00, 280.00, 2130.00),
	(19, 8, 'Трамп', 'Трамповски', '', '2223333444', '72@72.ru', 'dev-no', '', '', '', '', 'card', '', '2021-01-20 09:02:13', 0, 4667.00, 0.00, 4667.00),
	(20, 27, 'Купейко', 'Андрей', '', '22222', 'kup@kup.com', 'dev-no', '', '', '', '', 'card', '', '2021-01-20 20:38:40', 0, 1.00, 280.00, 281.00),
	(21, 27, 'Иванов', 'Иван', '', '23235456', 'kupi@kupi.ru', 'dev-yes', 'Москва', 'Ленина', '2', '25', 'card', '', '2021-01-20 20:53:16', 0, 1.00, 280.00, 281.00);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;

-- Дамп структуры для таблица fashion_shop.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `description` text,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `active` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы fashion_shop.products: ~19 rows (приблизительно)
DELETE FROM `products`;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `active`) VALUES
	(1, 'Платье со складками', NULL, 2000.00, '/img/products/product-1.jpg', 1),
	(2, 'Абвгдейка', NULL, 1850.00, '/img/products/product-2.jpg', 1),
	(3, 'Батарейка', NULL, 2999.00, '/img/products/product-3.jpg', 1),
	(4, 'Юла', NULL, 3590.00, '/img/products/product-4.jpg', 1),
	(5, 'Патифон', NULL, 2999.00, '/img/products/product-5.jpg', 1),
	(6, 'ArrayMassivve', NULL, 2999.00, '/img/products/product-6.jpg', 1),
	(7, 'Solaris', NULL, 2999.00, '/img/products/product-7.jpg', 1),
	(8, 'Лада', NULL, 4667.00, '/img/products/product-8.jpg', 1),
	(9, 'Платье со складками', NULL, 2999.00, '/img/products/product-9.jpg', 1),
	(10, 'Тестовое второе', NULL, 1599.00, '/img/products/product-9.jpg', 1),
	(11, 'Перчатки', NULL, 2800.00, '/img/products/product-9.jpg', 1),
	(12, 'Арбалет', NULL, 2850.00, '/img/products/product-9.jpg', 1),
	(13, 'Vino', NULL, 5010.00, '/img/products/product-9.jpg', 1),
	(18, 'Новый товар 1', NULL, 1550.00, '/img/products/coding.png', 1),
	(19, 'Боты аля кросы', NULL, 2500.00, '/img/products/bot1.jpg', 1),
	(20, 'Туфли отличные', NULL, 2549.00, '/img/products/8332340.jpg', 1),
	(21, 'Новый товар 1', NULL, 2000.00, '/img/products/coding.png', 1),
	(22, 'Новый товар 1', NULL, 2100.00, '/img/products/coding.png', 1),
	(23, 'Абвгдейка - 5', NULL, 2150.00, '/img/products/8308710.jpg', 1),
	(24, 'Моторное масло Wolf', NULL, 1950.00, '/img/products/8308710.jpg', 1),
	(25, 'Проба пера 2', NULL, 2450.00, '/img/products/8311192.jpg', 0),
	(26, 'Полуботинки6', NULL, 2222.00, '/img/products/8330469.jpg', 1),
	(27, 'Рублёвый товар', NULL, 1.00, '/img/products/product-3.jpg', 1),
	(28, 'Пробный ботинок', NULL, 2300.00, '/img/products/coding.png', 1);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Дамп структуры для таблица fashion_shop.promotions
CREATE TABLE IF NOT EXISTS `promotions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы fashion_shop.promotions: ~2 rows (приблизительно)
DELETE FROM `promotions`;
/*!40000 ALTER TABLE `promotions` DISABLE KEYS */;
INSERT INTO `promotions` (`id`, `name`, `description`) VALUES
	(1, 'new', 'Новинка'),
	(2, 'sale', 'Распродажа');
/*!40000 ALTER TABLE `promotions` ENABLE KEYS */;

-- Дамп структуры для таблица fashion_shop.promotion_product
CREATE TABLE IF NOT EXISTS `promotion_product` (
  `promotion_id` int NOT NULL,
  `product_id` int NOT NULL,
  PRIMARY KEY (`promotion_id`,`product_id`),
  KEY `FK_promotion_product_products` (`product_id`),
  CONSTRAINT `FK_promotion_product_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `FK_promotion_product_promotions` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы fashion_shop.promotion_product: ~16 rows (приблизительно)
DELETE FROM `promotion_product`;
/*!40000 ALTER TABLE `promotion_product` DISABLE KEYS */;
INSERT INTO `promotion_product` (`promotion_id`, `product_id`) VALUES
	(1, 1),
	(1, 2),
	(2, 2),
	(2, 4),
	(2, 5),
	(2, 9),
	(2, 13),
	(2, 19),
	(2, 21),
	(1, 22),
	(1, 23),
	(2, 23),
	(1, 24),
	(1, 27),
	(2, 27);
/*!40000 ALTER TABLE `promotion_product` ENABLE KEYS */;

-- Дамп структуры для таблица fashion_shop.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы fashion_shop.users: ~1 rows (приблизительно)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
	(1, 'ivan', 'admin@admin.ru', '$2y$10$2cOF4UeI.HIqyvDbiuCe8eNd5NPEfUswns0m5KkzbkBfLcEhOz3sG'),
	(2, 'oper1', 'oper@oper.ru', '$2y$10$2cOF4UeI.HIqyvDbiuCe8eNd5NPEfUswns0m5KkzbkBfLcEhOz3sG');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Дамп структуры для триггер fashion_shop.orders_before_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `orders_before_insert` BEFORE INSERT ON `orders` FOR EACH ROW BEGIN
SET NEW.created_at = NOW();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
