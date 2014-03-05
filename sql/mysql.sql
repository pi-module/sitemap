CREATE TABLE `{url_list}` ( 
  `id` int(10) unsigned NOT NULL auto_increment,
  `loc` varchar(255) NOT NULL,
  `lastmod` varchar(64) NOT NULL,
  `changefreq` varchar(64) NOT NULL,
  `priority` varchar(64) NOT NULL,
  `time_create` int(10) unsigned NOT NULL,
  `module` varchar(64) NOT NULL,
  `table` varchar(64) NOT NULL,
  `item` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loc` (`loc`),
  UNIQUE KEY `loc_unique` (`module`, `table`, `item`),
  KEY `status` (`status`),
  KEY `time_create` (`time_create`),
  KEY `module` (`module`),
  KEY `table` (`table`),
  KEY `item` (`item`),
  KEY `create_id` (`id`, `time_create`, `status`),
  KEY `module_table` (`module`, `table`)
); 

CREATE TABLE `{url_top}` ( 
  `id` int(10) unsigned NOT NULL auto_increment,
  `loc` varchar(255) NOT NULL,
  `lastmod` varchar(64) NOT NULL,
  `changefreq` varchar(64) NOT NULL,
  `priority` varchar(64) NOT NULL,
  `time_create` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loc` (`loc`),
  KEY `time_create` (`time_create`),
  KEY `create_id` (`id`, `time_create`),
  KEY `order_id` (`id`, `order`)
);

CREATE TABLE `{generate}` ( 
  `id` int(10) unsigned NOT NULL auto_increment,
  `file` varchar(64) NOT NULL,
  `time_create` int(10) unsigned NOT NULL,
  `time_update` int(10) unsigned NOT NULL,
  `start` int(10) unsigned NOT NULL,
  `end` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file` (`file`),
  KEY `time_create` (`time_create`)
);