CREATE TABLE `person_family` (`id` SMALLINT NOT NULL AUTO_INCREMENT , `person_id` SMALLINT NOT NULL , `name` VARCHAR(60) NOT NULL , `address` TEXT NULL , `phone` VARCHAR(15) NULL , `status` VARCHAR(30) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `person` ADD `join_date` DATE NULL AFTER `avatar`, ADD `status` ENUM('Aktif','Tidak Aktif') NOT NULL DEFAULT 'Tidak Aktif' AFTER `join_date`;