ALTER TABLE `pinjaman` ADD `year` VARCHAR(4) NOT NULL AFTER `person`, ADD `month` VARCHAR(2) NOT NULL AFTER `year`;

ALTER TABLE `pinjaman` ADD `status` ENUM('Approved','Decline','Pending') NOT NULL AFTER `balance`;

CREATE TABLE `koperassi`.`angsuran` (`id` INT NOT NULL AUTO_INCREMENT , `year` VARCHAR(4) NOT NULL , `month` VARCHAR(2) NOT NULL , `month_no` VARCHAR(2) NOT NULL , `pokok` FLOAT NOT NULL , `bunga` FLOAT NOT NULL , `status` ENUM('Approved','Decline','Pending') NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `pinjaman` ADD `angsuran` INT NOT NULL AFTER `date`;

ALTER TABLE `angsuran` ADD `pinjaman` INT NOT NULL AFTER `id`;

ALTER TABLE `angsuran` CHANGE `status` `status` ENUM('Lunas','Belum Lunas') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

ALTER TABLE `angsuran` ADD `date` DATE NOT NULL AFTER `pinjaman`;

ALTER TABLE `pinjaman` ADD `real` FLOAT NOT NULL AFTER `bunga`;

ALTER TABLE `pinjaman` ADD `reason` TEXT NULL AFTER `real`;