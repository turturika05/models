-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 02 2024 г., 21:47
-- Версия сервера: 8.0.30
-- Версия PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `models`
--

-- --------------------------------------------------------

--
-- Структура таблицы `castings`
--

CREATE TABLE `castings` (
  `casting_id` int NOT NULL,
  `casting_name` varchar(255) NOT NULL,
  `description` text,
  `casting_date` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `client_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `castings`
--

INSERT INTO `castings` (`casting_id`, `casting_name`, `description`, `casting_date`, `location`, `client_id`) VALUES
(2, 'Кастинг для модного показа', 'Кастинг для участия в модном показе', '2024-08-15', 'Санкт-Петербург', 2),
(3, 'Кастинг для фильма', 'Кастинг для съемок в фильме', '2024-09-10', 'Екатеринбург', 3),
(4, 'Кастинг для фотосессии', 'Кастинг для участия в фотосессии', '2024-10-05', 'Новосибирск', 4),
(5, 'Кастинг для журнала', 'Кастинг для съемок для обложки журнала', '2024-11-20', 'Казань', 5),
(6, 'Название кастинга', 'Описание кастинга', '2024-07-02', 'Москва', 6);

-- --------------------------------------------------------

--
-- Структура таблицы `casting_applications`
--

CREATE TABLE `casting_applications` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `casting_id` int DEFAULT NULL,
  `message` text,
  `application_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `casting_applications`
--

INSERT INTO `casting_applications` (`id`, `name`, `phone`, `email`, `casting_id`, `message`, `application_date`, `user_id`) VALUES
(2, 'Анна Кузнецова', '+7-900-789-01-23', 'anna@mail.ru', 2, 'Хочу участвовать в модном показе', '2024-06-24 17:00:06', 2),
(3, 'Павел Морозов', '+7-900-890-12-34', 'pavel@mail.ru', 3, 'Хочу сниматься в фильме', '2024-06-24 17:00:06', 3),
(4, 'Елена Ильина', '+7-900-901-23-45', 'elena@mail.ru', 4, 'Хочу участвовать в фотосессии', '2024-06-24 17:00:06', 4),
(5, 'Николай Сидоров', '+7-900-012-34-56', 'nikolay@mail.ru', 5, 'Хочу сниматься для обложки журнала', '2024-06-24 17:00:06', 5),
(6, '123', '+3753360350', 'merlber@mail.ru', 5, 'вапвап', '2024-06-27 00:30:58', NULL),
(9, 'Тестовая запись', '+799999999', 'user28@mail.ru', 3, 'Тестовая запись на модельный кастинг', '2024-07-02 08:27:15', NULL),
(10, '', '', '', 2, '', '2024-07-02 08:53:28', 7),
(11, '', '', '', 6, '', '2024-07-02 08:58:19', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE `clients` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `clients`
--

INSERT INTO `clients` (`id`, `name`, `contact_info`, `address`, `phone`, `email`) VALUES
(1, 'ООО \"Рога и Копыта\"', 'Контактное лицо: Иван Иванов', 'г. Москва, ул. Пушкина, д. 10', '+7-495-123-45-67', 'info@rogai-kopyta.ru'),
(2, 'АО \"Заря\"', 'Контактное лицо: Мария Петрова', 'г. Санкт-Петербург, Невский проспект, д. 20', '+7-812-234-56-78', 'contact@zarya.ru'),
(3, 'ООО \"Свет и Тень\"', 'Контактное лицо: Алексей Смирнов', 'г. Екатеринбург, ул. Ленина, д. 5', '+7-343-345-67-89', 'info@svetiten.ru'),
(4, 'АО \"Стиль и Мода\"', 'Контактное лицо: Ольга Соколова', 'г. Новосибирск, Красный проспект, д. 15', '+7-383-456-78-90', 'info@stylemoda.ru'),
(5, 'ООО \"Мир моделей\"', 'Контактное лицо: Дмитрий Орлов', 'г. Казань, ул. Баумана, д. 25', '+7-843-567-89-01', 'contact@modelmir.ru'),
(6, 'Новый клиент', 'Новый клиент', 'Новый клиент', '+799999999', 'client1@mail.ru');

-- --------------------------------------------------------

--
-- Структура таблицы `contracts`
--

CREATE TABLE `contracts` (
  `id` int NOT NULL,
  `model_id` int DEFAULT NULL,
  `client_id` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `contracts`
--

INSERT INTO `contracts` (`id`, `model_id`, `client_id`, `start_date`, `end_date`) VALUES
(2, 2, 2, '2024-02-01', '2024-11-30'),
(3, 4, 3, '2024-03-01', '2024-10-31');

-- --------------------------------------------------------

--
-- Структура таблицы `models`
--

CREATE TABLE `models` (
  `id` int NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `height` int DEFAULT NULL,
  `weight` int DEFAULT NULL,
  `hair_color` varchar(20) DEFAULT NULL,
  `experience_level` enum('Beginner','Intermediate','Experienced') DEFAULT NULL,
  `active_contract` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `models`
--

INSERT INTO `models` (`id`, `first_name`, `last_name`, `gender`, `birth_date`, `height`, `weight`, `hair_color`, `experience_level`, `active_contract`) VALUES
(2, 'Мария', 'Петрова', 'Female', '1992-02-20', 170, 60, 'Блондинка', 'Intermediate', 1),
(4, 'Ольга', 'Смирнова', 'Female', '1996-04-15', 165, 55, 'Рыжая', 'Experienced', 1),
(5, 'Дмитрий', 'Орлов', 'Male', '1998-05-25', 175, 70, 'Шатен', 'Intermediate', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `model_photos`
--

CREATE TABLE `model_photos` (
  `id` int NOT NULL,
  `model_id` int DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `model_photos`
--

INSERT INTO `model_photos` (`id`, `model_id`, `file_path`) VALUES
(2, 2, '../../uploads/1598003864_f2_0.jpg'),
(4, 4, '../../uploads/1598003864_f2_0.jpg'),
(5, 5, '../../uploads/46315702_1908208855893527_5092316175268124698_n.jpg'),
(10, 2, '../../uploads/46315702_1908208855893527_5092316175268124698_n.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `model_skills`
--

CREATE TABLE `model_skills` (
  `id` int NOT NULL,
  `model_id` int DEFAULT NULL,
  `skill_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `model_skills`
--

INSERT INTO `model_skills` (`id`, `model_id`, `skill_id`) VALUES
(5, 5, 5),
(6, 2, 2),
(7, 4, 4),
(8, 4, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `photoshoots`
--

CREATE TABLE `photoshoots` (
  `id` int NOT NULL,
  `photoshoots_name` varchar(255) DEFAULT NULL,
  `description` text,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `photoshoots`
--

INSERT INTO `photoshoots` (`id`, `photoshoots_name`, `description`, `date`) VALUES
(1, 'Фотосессия в парке', 'Фотосессия на природе в парке', '2024-06-01'),
(2, 'Городская фотосессия', 'Фотосессия на улицах города', '2024-07-01'),
(3, 'Студийная фотосессия', 'Профессиональная фотосессия', '2024-08-01'),
(4, 'Морская фотосессия', 'Фотосессия на берегу моря', '2024-09-01');

-- --------------------------------------------------------

--
-- Структура таблицы `photoshoot_models`
--

CREATE TABLE `photoshoot_models` (
  `photoshoot_id` int NOT NULL,
  `model_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `photoshoot_models`
--

INSERT INTO `photoshoot_models` (`photoshoot_id`, `model_id`) VALUES
(2, 2),
(4, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `photoshoot_photos`
--

CREATE TABLE `photoshoot_photos` (
  `id` int NOT NULL,
  `photoshoot_id` int NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `photoshoot_photos`
--

INSERT INTO `photoshoot_photos` (`id`, `photoshoot_id`, `file_path`, `uploaded_at`) VALUES
(1, 1, '../../uploads/photoshoot_images/1598003864_f2_0.jpg', '2024-06-24 17:00:06'),
(2, 2, '../../uploads/photoshoot_images/46315702_1908208855893527_5092316175268124698_n.jpg', '2024-06-24 17:00:06'),
(3, 3, '../../uploads/photoshoot_images/1598003864_f2_0.jpg', '2024-06-24 17:00:06'),
(4, 4, '../../uploads/photoshoot_images/46315702_1908208855893527_5092316175268124698_n.jpg', '2024-06-24 17:00:06');

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `post_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `main_text` text,
  `category` varchar(255) NOT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`post_id`, `title`, `image_path`, `main_text`, `category`, `user_id`, `created_at`) VALUES
(1, 'Новое событие в мире моды', '../../uploads/posts/photo.jpg', 'Описание нового события в мире моды', 'Мода', 1, '2024-06-24 17:00:06'),
(2, 'Советы по фотосессиям', '../../uploads/posts/photo.jpg', 'Полезные советы для успешной фотосессии', 'Фотография', 2, '2024-06-24 17:00:06'),
(3, 'Интервью с моделью', '../../uploads/posts/photo.jpg', 'Интервью с известной моделью', 'Интервью', 3, '2024-06-24 17:00:06'),
(5, 'Закулисье модного показа', '../../uploads/posts/photo.jpg', 'Рассказы о том, что происходит за кулисами модного показа', 'Мода', 5, '2024-06-24 17:00:06');

-- --------------------------------------------------------

--
-- Структура таблицы `skills`
--

CREATE TABLE `skills` (
  `id` int NOT NULL,
  `skill_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `skills`
--

INSERT INTO `skills` (`id`, `skill_name`) VALUES
(1, 'Модельная походка'),
(2, 'Профессиональная фотосессия'),
(3, 'Дефиле'),
(4, 'Актерское мастерство'),
(5, 'Танцы'),
(7, 'Вокал');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_admin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `email`, `phone_number`, `date_of_birth`, `full_name`, `password`, `registration_date`, `is_admin`) VALUES
(1, 'user1@mail.ru', '+79001234568', '2005-05-03', 'Иванов Александр', '$2y$10$1ANDOOnc9SuvBfs9/rFNguAR9n1kX9NUTh4yT4QiLZGX3OVBhxEFe', '2024-06-24 17:00:06', 0),
(2, 'user2@mail.ru', '+7-900-234-56-78', '1992-02-02', 'Мария Петрова', '$2y$10$DGUVdokC9U6.9whSAQhRIeSQ6ipRsJKq91Yg86VP3VFxBNpZcC/Q2', '2024-06-24 17:00:06', 0),
(3, 'user3@mail.ru', '+7-900-345-67-89', '1994-03-03', 'Иван Смирнов', '$2y$10$DGUVdokC9U6.9whSAQhRIeSQ6ipRsJKq91Yg86VP3VFxBNpZcC/Q2', '2024-06-24 17:00:06', 0),
(4, 'user4@mail.ru', '+7-900-456-78-90', '1996-04-04', 'Ольга Соколова', '$2y$10$DGUVdokC9U6.9whSAQhRIeSQ6ipRsJKq91Yg86VP3VFxBNpZcC/Q2', '2024-06-24 17:00:06', 0),
(5, 'admin@mail.ru', '+73805012345', '2024-05-31', 'Дмитрий Орликов', '$2y$10$RCgr7vuWK4D6GIMZXObxWOb0qiQjw2BB6S6o.52mdfQTgOnrTyuja', '2024-06-24 17:00:06', 1),
(6, 'admin@example.com', NULL, NULL, NULL, '$2y$10$4AG/I.Ea0ZA6npeKOjvJCuOHrSsSKBu4tdDzOoRx2xzHPLkKaxSlC', '2024-06-27 23:49:03', 0),
(7, 'user28@mail.ru', '+799999999', '2001-10-02', 'Иванов Иван', '$2y$10$hoRRSIdrCjUj.rPYVB1xkuOMf60.XW9V3/4D6bqNqaVzqk7QE4.7y', '2024-07-02 08:52:45', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `castings`
--
ALTER TABLE `castings`
  ADD PRIMARY KEY (`casting_id`),
  ADD KEY `castings_ibfk_1` (`client_id`);

--
-- Индексы таблицы `casting_applications`
--
ALTER TABLE `casting_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `casting_applications_ibfk_1` (`casting_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Индексы таблицы `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contracts_ibfk_1` (`model_id`),
  ADD KEY `contracts_ibfk_2` (`client_id`);

--
-- Индексы таблицы `models`
--
ALTER TABLE `models`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `model_photos`
--
ALTER TABLE `model_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_photos_ibfk_1` (`model_id`);

--
-- Индексы таблицы `model_skills`
--
ALTER TABLE `model_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_skills_ibfk_1` (`model_id`),
  ADD KEY `model_skills_ibfk_2` (`skill_id`);

--
-- Индексы таблицы `photoshoots`
--
ALTER TABLE `photoshoots`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `photoshoot_models`
--
ALTER TABLE `photoshoot_models`
  ADD PRIMARY KEY (`photoshoot_id`,`model_id`),
  ADD KEY `photoshoot_models_ibfk_2` (`model_id`);

--
-- Индексы таблицы `photoshoot_photos`
--
ALTER TABLE `photoshoot_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photoshoot_photos_ibfk_1` (`photoshoot_id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `posts_ibfk_1` (`user_id`);

--
-- Индексы таблицы `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `castings`
--
ALTER TABLE `castings`
  MODIFY `casting_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `casting_applications`
--
ALTER TABLE `casting_applications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `models`
--
ALTER TABLE `models`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `model_photos`
--
ALTER TABLE `model_photos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `model_skills`
--
ALTER TABLE `model_skills`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `photoshoots`
--
ALTER TABLE `photoshoots`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `photoshoot_photos`
--
ALTER TABLE `photoshoot_photos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `castings`
--
ALTER TABLE `castings`
  ADD CONSTRAINT `castings_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `casting_applications`
--
ALTER TABLE `casting_applications`
  ADD CONSTRAINT `casting_applications_ibfk_1` FOREIGN KEY (`casting_id`) REFERENCES `castings` (`casting_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`),
  ADD CONSTRAINT `contracts_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Ограничения внешнего ключа таблицы `model_photos`
--
ALTER TABLE `model_photos`
  ADD CONSTRAINT `model_photos_ibfk_1` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `model_skills`
--
ALTER TABLE `model_skills`
  ADD CONSTRAINT `model_skills_ibfk_1` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`),
  ADD CONSTRAINT `model_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`);

--
-- Ограничения внешнего ключа таблицы `photoshoot_models`
--
ALTER TABLE `photoshoot_models`
  ADD CONSTRAINT `photoshoot_models_ibfk_1` FOREIGN KEY (`photoshoot_id`) REFERENCES `photoshoots` (`id`),
  ADD CONSTRAINT `photoshoot_models_ibfk_2` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`);

--
-- Ограничения внешнего ключа таблицы `photoshoot_photos`
--
ALTER TABLE `photoshoot_photos`
  ADD CONSTRAINT `photoshoot_photos_ibfk_1` FOREIGN KEY (`photoshoot_id`) REFERENCES `photoshoots` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
