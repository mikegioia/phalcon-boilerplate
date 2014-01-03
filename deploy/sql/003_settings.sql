INSERT INTO  `inserts` ( `name` , `created_at` )
VALUES ( '003_settings', NOW( ) );

CREATE TABLE IF NOT EXISTS `settings` (
  `object_id` int(10) unsigned NOT NULL,
  `object_type` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE  `settings` ADD UNIQUE ( `object_id` , `object_type` , `key` );
ALTER TABLE  `settings` ADD INDEX (  `object_id` ,  `object_type` ) ;
