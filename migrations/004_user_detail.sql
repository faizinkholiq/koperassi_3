ALTER TABLE `person` ADD `position` TINYINT(2) NULL AFTER `address`, ADD `depo` VARCHAR(30) NULL AFTER `position`, ADD `acc_no` VARCHAR(50) NULL AFTER `depo`;
CREATE TABLE `position` (`id` TINYINT(2) NOT NULL AUTO_INCREMENT , `name` VARCHAR(50) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
INSERT INTO `position` (`id`, `name`) VALUES (1, 'Administrator'), (2, 'Staff');