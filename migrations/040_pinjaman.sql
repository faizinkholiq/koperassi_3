ALTER TABLE `pinjaman` ADD `year` VARCHAR(4) NOT NULL AFTER `person`, ADD `month` VARCHAR(2) NOT NULL AFTER `year`;

ALTER TABLE `pinjaman` ADD `status` ENUM('Approved','Decline','Pending') NOT NULL AFTER `balance`;

CREATE TABLE `koperassi`.`angsuran` (`id` INT NOT NULL AUTO_INCREMENT , `year` VARCHAR(4) NOT NULL , `month` VARCHAR(2) NOT NULL , `month_no` VARCHAR(2) NOT NULL , `pokok` FLOAT NOT NULL , `bunga` FLOAT NOT NULL , `status` ENUM('Approved','Decline','Pending') NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;