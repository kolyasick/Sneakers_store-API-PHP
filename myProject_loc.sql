-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 23 2024 г., 18:26
-- Версия сервера: 10.8.4-MariaDB
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `myProject.loc`
--

-- --------------------------------------------------------

--
-- Структура таблицы `articles`
--

CREATE TABLE `articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `author_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `articles`
--

INSERT INTO `articles` (`id`, `author_id`, `name`, `text`, `created_at`) VALUES
(1, 1, 'Статья о том, как я погулял', 'Шёл я значит по тротуару, как вдруг...', '2024-02-15 00:18:11'),
(2, 1, 'Пост о жизни', 'Сидел я тут на кухне с друганом и тут он задал такой вопрос...', '2024-02-15 00:18:11'),
(3, 5, 'Коля', 'sadasdas', '2024-04-10 15:13:44');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `title`, `description`) VALUES
(1, 0, 'Adidas', 'Крутые кроссовки'),
(2, 1, 'Adidas Gazzele', 'Для четких пацанов'),
(3, 1, 'Adidas Forum ', 'Для четких пацанов'),
(4, 0, 'Nike', 'Для женщин'),
(5, 0, 'Puma', 'Для всех'),
(6, 0, 'Demix', 'Для нищих'),
(7, 4, 'Nike Dunk', 'Стильные кроссовки'),
(8, 4, 'Nike Air Jordan', 'Кроссовки для баскетбола');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `qty` tinyint(3) UNSIGNED DEFAULT NULL,
  `total` decimal(10,0) DEFAULT 0,
  `status` tinyint(4) DEFAULT 0,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `created_at`, `updated_at`, `qty`, `total`, `status`, `name`, `email`, `phone`, `address`, `note`) VALUES
(28, '2024-04-12 16:40:05', NULL, 4, '23796', 1, 'Коля', 'igrokfaceit21@gmail.com', '23123', 'asdas', 'dasdas'),
(29, '2024-04-18 10:21:00', NULL, 2, '14398', 1, 'КОля', 'asdasd@mail.ru', '89423423333', 'sdasdasdasd', 'DSADASDSDSD'),
(30, '2024-04-18 10:38:40', NULL, 2, '13398', 1, 'КОля', 'asdasd@mail.ru', '89423423333', 'sdasdasdasd', 'asdasdas'),
(32, '2024-04-20 16:02:48', NULL, 1, '5699', 0, 'Коля', 'asdasd@mail.ru', '2312312312', 'adsasdqwdasdasd', 'asdasdasdwqdgfhfgh6klvsd');

-- --------------------------------------------------------

--
-- Структура таблицы `order_product`
--

CREATE TABLE `order_product` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED DEFAULT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `price` decimal(10,0) DEFAULT 0,
  `qty` tinyint(4) DEFAULT NULL,
  `total` decimal(10,0) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `order_product`
--

INSERT INTO `order_product` (`id`, `order_id`, `product_id`, `title`, `price`, `qty`, `total`) VALUES
(18, 30, 2, 'Кроссовки Adidas L12', '4699', 1, '4699'),
(19, 30, 1, 'Кроссовки Adidas M23', '8699', 1, '8699'),
(20, 31, 1, 'Кроссовки Adidas M23', '8699', 1, '8699'),
(21, 31, 2, 'Кроссовки Adidas L12', '4699', 1, '4699'),
(22, 32, 4, 'Кроссовки Nike P342', '5699', 1, '5699');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `price` decimal(6,2) DEFAULT 0.00,
  `old_price` decimal(6,2) NOT NULL DEFAULT 0.00,
  `description` varchar(255) DEFAULT NULL,
  `img` varchar(255) NOT NULL DEFAULT 'no-image.png',
  `is_offer` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `category_id`, `title`, `content`, `price`, `old_price`, `description`, `img`, `is_offer`) VALUES
(1, 1, 'Кроссовки Adidas M23', 'Для бега', '8699.00', '7999.00', 'Для всех', 'sneakers-1.jpg', 0),
(2, 1, 'Кроссовки Adidas L12', 'Для бега', '4699.00', '6999.00', 'Для всех', 'sneakers-2.jpg', 0),
(3, 1, 'Кроссовки Adidas S21', 'Для бега', '5699.00', '6999.00', 'Для всех', 'sneakers-3.jpg', 0),
(4, 4, 'Кроссовки Nike P342', 'Для повседневной ходьбы', '5699.00', '6999.00', 'Для всех', 'sneakers-4.jpg', 0),
(5, 4, 'Кроссовки Nike A24', 'Для повседневной ходьбы', '7699.00', '8999.00', 'Для всех', 'sneakers-5.jpg', 0),
(6, 4, 'Кроссовки Nike L23', 'Для повседневной ходьбы', '2699.00', '4999.00', 'Для всех', 'sneakers-6.jpg', 0),
(7, 5, 'Кроссовки Puma L2312', 'Для спорта', '1699.00', '3999.00', 'Для всех', 'sneakers-7.jpg', 0),
(8, 5, 'Кроссовки Puma X231', 'Для спорта', '8699.00', '9999.00', 'Для всех', 'sneakers-8.jpg', 0),
(9, 6, 'Кроссовки Demix H231', 'Для спорта и не только', '6699.00', '7999.00', 'Для всех и не только', 'sneakers-9.jpg', 0),
(10, 4, 'Кроссовки Nike M231', 'Для повседневной ходьбы', '3699.00', '4999.00', 'Для всех', 'sneakers-10.jpg', 0),
(11, 4, 'Кроссовки Nike A23', 'Для повседневной ходьбы', '6700.00', '8000.00', 'Для всех', 'sneakers-11.jpg', 0),
(12, 5, 'Кроссовки Puma BH12', 'Для спорта', '4200.00', '4500.00', 'Для всех', 'sneakers-12.jpg', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT 'Anonymus',
  `text` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `is_confirmed` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`id`, `name`, `text`, `rating`, `is_confirmed`) VALUES
(1, 'Коля', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus, velit nobis delectus maxime voluptatum necessitatibus sunt voluptatem tempora ipsum in.\n', 1, 1),
(2, 'Артур Смирнов', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus, velit nobis delectus maxime voluptatum necessitatibus sunt voluptatem tempora ipsum in.\n', 5, 0),
(3, 'Иван', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus, velit nobis delectus maxime voluptatum necessitatibus sunt voluptatem tempora ipsum in.\r\n', 4, 0),
(4, 'Алдияр', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus, velit nobis delectus maxime voluptatum necessitatibus sunt voluptatem tempora ipsum in.\r\n', 2, 0),
(5, 'Ассылжон', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus, velit nobis delectus maxime voluptatum necessitatibus sunt voluptatem tempora ipsum in.\r\n', 4, 0),
(6, 'Миша', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus, velit nobis delectus maxime voluptatum necessitatibus sunt voluptatem tempora ipsum in.\r\n', 4, 0),
(7, 'Андрей', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus, velit nobis delectus maxime voluptatum necessitatibus sunt voluptatem tempora ipsum in.\r\n', 4, 0),
(8, 'Алишер', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus, velit nobis delectus maxime voluptatum necessitatibus sunt voluptatem tempora ipsum in.\n', 4, 1),
(15, 'Коляasda', 'asdasdasdasdasdasasd', 4, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nickname` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `role` enum('admin','user') NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `auth_token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `nickname`, `email`, `is_confirmed`, `role`, `password_hash`, `auth_token`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', 1, 'admin', 'hash1', '155ffee9fbefc0f292de262e7bcebb09209020fcb429b0966a261ae3d81413c7153ed0f94bf08fed', '2024-02-15 00:14:42'),
(2, 'user', 'user@gmail.com', 1, 'user', 'hash2', 'token2', '2024-02-15 00:14:42'),
(3, 'kolya', 'pasdel@mail.ru', 1, 'user', '$2y$10$alwqOtgFB4JJGpMup/AEiexLYx0Txwwl/MH2Dm3h/UR/m/0cmCUIG', '856804cdb54fca7446672695c87fea39a82ba579c5e04fe11e20be9ba716b4b97695d92df3ad75ac', '2024-03-20 14:16:05'),
(5, 'Nikolaj', 'asdasd@mail.ru', 1, 'admin', '$2y$10$Vi1IbZ8yu0/6GOZ.vJCqbefoWNKr2kwO/pWayTSocoPNiEWqFpGBu', 'a498a52536efafeb68c7e3228a3cfe0ec2b5a554d04ff4773016c738690f0f2a81de7c31f7d8ba3e', '2024-03-20 15:23:34'),
(6, 'TestAcc', 'user@mail.ru', 1, 'user', '$2y$10$eoAgUMz2nNIP9mf.UPkuQOOM.OhrueoEMXe4qpJm/NBf5yCjgwAe6', 'd832032f88744f739b564f72e75dd3719cb2debc312e227f657923f4b2228a4c777c0e2542f4a6fc', '2024-04-20 14:41:56');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблицы `order_product`
--
ALTER TABLE `order_product`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
