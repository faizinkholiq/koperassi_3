CREATE TABLE `notification` (`id` INT NOT NULL AUTO_INCREMENT , `user_id` SMALLINT(6) NOT NULL , `time` DATE NOT NULL , `message` TEXT NOT NULL , `status` ENUM("Success","Pending","Failed") NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `notification` ADD `module` VARCHAR(50) NOT NULL AFTER `message`;
ALTER TABLE `notification` ADD `changes_id` INT NOT NULL AFTER `module`;