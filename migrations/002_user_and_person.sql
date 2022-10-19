INSERT INTO `user` (`id`, `username`, `name`, `role`, `password`, `active`) VALUES ('2', 'member', 'Member', '2', 'member', '1');
INSERT INTO `user_detail` (`id`, `user_id`, `name`, `nik`, `tmk`, `address`, `phone`, `email`, `ktp`, `avatar`) VALUES ('2', '2', 'Member', '0987654321', '123456', 'Somewhere', '088344323', 'member@koperasi.com', NULL, NULL);
ALTER TABLE user_detail RENAME TO person;