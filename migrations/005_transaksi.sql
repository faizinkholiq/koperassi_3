CREATE TABLE `simpanan_pokok` (`id` INT NOT NULL AUTO_INCREMENT , `person` INT NOT NULL , `code` VARCHAR(20) NOT NULL , `date` DATE NOT NULL , `balance` FLOAT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `simpanan_wajib` (`id` INT NOT NULL AUTO_INCREMENT , `person` INT NOT NULL , `code` VARCHAR(20) NOT NULL , `date` DATE NOT NULL , `balance` FLOAT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `simpanan_sukarela` (`id` INT NOT NULL AUTO_INCREMENT , `person` INT NOT NULL , `code` VARCHAR(20) NOT NULL , `date` DATE NOT NULL , `balance` FLOAT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `pinjaman` (`id` INT NOT NULL AUTO_INCREMENT , `person` INT NOT NULL , `date` DATE NOT NULL , `pinjaman` FLOAT NOT NULL , `bunga` FLOAT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `cicilan` (`id` INT NOT NULL AUTO_INCREMENT , `pinjaman` INT NOT NULL , `date` DATE NOT NULL , `cicilan` FLOAT NOT NULL , `bayar` FLOAT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `pinjaman` CHANGE `pinjaman` `balance` FLOAT NOT NULL;