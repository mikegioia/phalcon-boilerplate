INSERT INTO  `inserts` ( `name` , `created_at` )
VALUES ( '002_users', NOW( ) );

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

INSERT INTO  `users` ( `id` , `email` , `password` , `name` , `created_at` )
VALUES (
    NULL ,  'test@example.org',  '$2a$10$4wtMMI613pcGWVzLlIFEbOCW/ni5RsSFoJ7Ygi6P18cc51gO4CUTC', 
    'Johnny Test', NOW() );