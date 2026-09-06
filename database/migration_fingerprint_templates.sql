CREATE TABLE IF NOT EXISTS `employee_fingerprint_templates` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `finger_slot` TINYINT UNSIGNED NOT NULL,
    `template_blob` MEDIUMBLOB NOT NULL,
    `device_sn` VARCHAR(150) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_employee_finger_slot` (`employee_id`, `finger_slot`),
    KEY `idx_template_employee_active` (`employee_id`, `is_active`),
    CONSTRAINT `fk_template_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;