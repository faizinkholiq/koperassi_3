CREATE TABLE `koperassi`.`pinjaman_barang` (`id` INT NOT NULL AUTO_INCREMENT , `person` VARCHAR(20) NOT NULL , `name` VARCHAR(100) NOT NULL , `buy` INT NOT NULL , `sell` INT NOT NULL , `date` DATE NOT NULL , `status` ENUM('Approved','Decline','Pending') NOT NULL , `reason` TEXT NOT NULL , `angsuran` INT(2) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `koperassi`.`angsuran_barang` (`id` INT NOT NULL AUTO_INCREMENT , `pinjaman` INT NOT NULL , `date` DATE NOT NULL , `year` INT(4) NOT NULL , `month` INT(2) NOT NULL , `month_no` INT(2) NOT NULL , `pokok` INT NOT NULL , `bunga` INT NOT NULL , `status` ENUM('Lunas','Belum Lunas') NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `angsuran_barang` CHANGE `pokok` `angsuran` INT(11) NOT NULL;

ALTER TABLE `angsuran_barang` DROP `bunga`;