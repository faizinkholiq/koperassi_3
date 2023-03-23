ALTER TABLE `pinjaman` ADD `year` VARCHAR(4) NOT NULL AFTER `person`, ADD `month` VARCHAR(2) NOT NULL AFTER `year`;

ALTER TABLE `pinjaman` ADD `status` ENUM('Approved','Decline','Pending') NOT NULL AFTER `balance`;