ALTER TABLE `person_temp` ADD `status` ENUM("Pending", "Approved", "Rejected") NOT NULL AFTER `ktp`;
ALTER TABLE `person_temp` ADD `reason` TEXT NULL AFTER `status`;