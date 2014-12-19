CREATE TABLE IF NOT EXISTS unit (
`id`                    INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
`name`                  VARCHAR(255) NOT NULL,
`display_name`          VARCHAR(255) NOT NULL,
`class_id`              INT UNSIGNED NOT NULL,
`rarity`                TINYINT(1) NOT NULL,
`max_lvl`               SMALLINT(3) NOT NULL,
`int`                   INT(10) UNSIGNED NOT NULL,
`agi`                   INT(10) UNSIGNED NOT NULL,
`str`                   INT(10) UNSIGNED NOT NULL,
`vit`                   INT(10) UNSIGNED NOT NULL,
`burst`                 INT(10) UNSIGNED NOT NULL,
`target_range`          VARCHAR(255) NOT NULL,
`is_starting_unit`      TINYINT(1) NOT NULL,
`unit_leader_skill_id`  INT UNSIGNED,
PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS class (
`id`              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
`name`            VARCHAR(255) NOT NULL,
`int_up_per_lvl`  DECIMAL(4,2) NOT NULL,
`agi_up_per_lvl`  DECIMAL(4,2) NOT NULL,
`str_up_per_lvl`  DECIMAL(4,2) NOT NULL,
`vit_up_per_lvl`  DECIMAL(4,2) NOT NULL,
PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS unit_leader_skill (
`id`               INT(10) UNSIGNED NOT NULL,
`name`             VARCHAR(255) NOT NULL,
`skill_effect`     VARCHAR(255) NOT NULL,
`effect_qty`       DECIMAL(4,2),
`description`      TEXT,
PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS player_units (
`player_id`       INT(10) UNSIGNED NOT NULL,
`unit_id`         INT(10) UNSIGNED NOT NULL,
`current_lvl`     SMALLINT(3) NOT NULL,
`exp_to_go`       SMALLINT(3) NOT NULL,
INDEX (player_id, unit_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS player (
`id`                INT(10) UNSIGNED NOT NULL,
`name`              VARCHAR(255) NOT NULL,
`level`             SMALLINT(3) NOT NULL,
`exp`               INT(10) UNSIGNED NOT NULL,
`energy`            SMALLINT(3) NOT NULL,
`unit_leader_id`    INT(10) UNSIGNED NOT NULL,
`gold`              INT(10) UNSIGNED NOT NULL COMMENT 'DEFAULT GAME CURRENCY',
`crystal`           INT(10) UNSIGNED NOT NULL COMMENT 'PURCHASED VIA REAL MONEY',
`last_login`        DATETIME,
`created`           DATETIME,
`updated`           TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
`stage_setting_id`  INT(10) UNSIGNED NOT NULL DEFAULT 1,
PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS player_id_finder (
`client_id`                VARCHAR(255) NOT NULL,
`player_id`                INT(10) UNSIGNED NOT NULL,
PRIMARY KEY (`client_id`),
UNIQUE KEY (`player_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS stage_setting (
id                    INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
level_setting_id      TINYINT(3) NOT NULL,
part                  TINYINT(3) NOT NULL,
unit_ids              VARCHAR(255) NOT NULL,
PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS level_setting (
id                    INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
world_id              TINYINT(3) NOT NULL,
world_sequence        TINYINT(3) NOT NULL,
name                  VARCHAR(255) NOT NULL,
display_name          VARCHAR(255) NOT NULL,
level_boss_id         INT(10) UNSIGNED NOT NULL,
PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS world (
id                    INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
name                  VARCHAR(255) NOT NULL,
display_name          VARCHAR(255) NOT NULL,
PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;