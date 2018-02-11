CREATE TABLE IF NOT EXISTS `presets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `widget_name` varchar(32) NOT NULL,
  `class_name` varchar(200) NOT NULL,
  `is_system` tinyint(1) unsigned NOT NULL,
  `is_responsive` tinyint(1) unsigned NOT NULL,
  `properties` text NOT NULL,
  `template` varchar(32) NOT NULL DEFAULT 'default',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`widget_name`),
  UNIQUE KEY `class_name` (`class_name`,`widget_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;