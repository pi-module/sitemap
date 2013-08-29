CREATE TABLE `{url_list}` ( 
  `id` int(10) unsigned NOT NULL auto_increment,
  `loc` varchar(255) NOT NULL,
  `lastmod` varchar(64) NOT NULL,
  `changefreq` varchar(64) NOT NULL,
  `priority` varchar(64) NOT NULL,
  `create` int(10) unsigned NOT NULL,
  `module` varchar(64) NOT NULL,
  `table` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `create` (`create`),
  KEY `module` (`module`),
  KEY `table` (`table`),
  KEY `create_id` (`id`, `create`),
  KEY `module_table` (`module`, `table`)
); 

CREATE TABLE `{url_top}` ( 
  `id` int(10) unsigned NOT NULL auto_increment,
  `loc` varchar(255) NOT NULL,
  `lastmod` varchar(64) NOT NULL,
  `changefreq` varchar(64) NOT NULL,
  `priority` varchar(64) NOT NULL,
  `create` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `create` (`create`),
  KEY `create_id` (`id`, `create`),
  KEY `order_id` (`id`, `order`)
);