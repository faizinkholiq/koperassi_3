CREATE TABLE `person_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `person_id` smallint(6) NOT NULL,
  `name` varchar(60) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `tmk` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `depo` varchar(15) DEFAULT NULL,
  `acc_no` varchar(50) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `ktp` text DEFAULT NULL,
   PRIMARY KEY (`id`)
);

ALTER TABLE `person` CHANGE `nik` `nik` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, CHANGE `tmk` `tmk` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;