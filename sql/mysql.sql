CREATE TABLE `{url}`
(
    `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `loc`         VARCHAR(255)        NOT NULL DEFAULT '',
    `lastmod`     VARCHAR(64)         NOT NULL DEFAULT '',
    `changefreq`  VARCHAR(64)         NOT NULL DEFAULT '',
    `priority`    VARCHAR(64)         NOT NULL DEFAULT '',
    `time_create` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `module`      VARCHAR(64)         NOT NULL DEFAULT '',
    `table`       VARCHAR(64)         NOT NULL DEFAULT '',
    `item`        INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    `top`         TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    UNIQUE KEY `loc` (`loc`),
    KEY `status` (`status`),
    KEY `time_create` (`time_create`),
    KEY `module` (`module`),
    KEY `table` (`table`),
    KEY `item` (`item`),
    KEY `top` (`top`),
    KEY `create_id` (`id`, `time_create`, `status`),
    KEY `module_table` (`module`, `table`),
    KEY `list_order` (`top`, `priority`, `time_create`)
);

CREATE TABLE `{generate}`
(
    `id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `file`        VARCHAR(64)      NOT NULL DEFAULT '',
    `time_create` INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `time_update` INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `start`       INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `end`         INT(10) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    UNIQUE KEY `file` (`file`),
    KEY `time_create` (`time_create`)
);