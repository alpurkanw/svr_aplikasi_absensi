CREATE TABLE IF NOT EXISTS `payroll_periods` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `period_year` SMALLINT UNSIGNED NOT NULL,
    `period_month` TINYINT UNSIGNED NOT NULL,
    `period_label` VARCHAR(50) NOT NULL,
    `period_start` DATE NOT NULL,
    `period_end` DATE NOT NULL,
    `status` ENUM('DRAFT','PROCESSING','REVIEW','FINALIZED') NOT NULL DEFAULT 'DRAFT',
    `processed_at` DATETIME NULL,
    `finalized_at` DATETIME NULL,
    `finalized_by` INT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_payroll_period_year_month` (`period_year`, `period_month`),
    KEY `idx_payroll_period_status` (`status`),
    CONSTRAINT `fk_payroll_period_finalized_by` FOREIGN KEY (`finalized_by`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
