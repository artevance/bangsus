ALTER TABLE `incoming_mutation` ADD `supplier_mutasi_id` BIGINT NULL AFTER `approve`;
ALTER TABLE `incoming_mutation` ADD FOREIGN KEY (`supplier_mutasi_id`) REFERENCES `supplier_mutasi`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
