CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `email` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `nom` varchar(200) NOT NULL,
  `prenom` varchar(200) NOT NULL
) ENGINE = MyISAM DEFAULT CHARSET = utf8;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(200) NOT NULL,
  `photo` text NOT NULL,
  `content` text,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = MyISAM DEFAULT CHARSET = utf8;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `content` text NOT NULL,
  `article_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`article_id`) REFERENCES `articles`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = MyISAM DEFAULT CHARSET = utf8;
-- INSERT INTO `users` (
--     `id`,
--     `email`,
--     `password`,
--     `nom`,
--     `prenom`
--   )
-- VALUES (
--     1,
--     'toto@mail.com',
--     'test123',
--     'Toto Nom',
--     'Toto Prenom'
--   );