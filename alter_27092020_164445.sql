ALTER TABLE `role` ADD `akses_semua_cabang` BOOLEAN NOT NULL AFTER `role_name`;
UPDATE `role` SET `akses_semua_cabang` = '1' WHERE `role`.`id` = 1;

CREATE TABLE `user_cabang` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `cabang_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `user_cabang`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `user_cabang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_cabang` ADD  FOREIGN KEY (`cabang_id`) REFERENCES `cabang`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `user_cabang` ADD  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

INSERT INTO user_cabang
SELECT NULL, user.id, cabang_id, UTC_TIMESTAMP, UTC_TIMESTAMP, NULL FROM `user`
LEFT JOIN (
  SELECT tugas_karyawan.id AS id, cabang_id, cabang FROM tugas_karyawan
    LEFT JOIN cabang ON cabang.id = tugas_karyawan.cabang_id
) AS tugas_karyawan ON user.tugas_karyawan_id = tugas_karyawan.id
LEFT JOIN role ON user.role_id = role.id
WHERE role_code = 'leader';