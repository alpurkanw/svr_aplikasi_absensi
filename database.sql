CREATE TABLE IF NOT EXISTS `presensi` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` VARCHAR(100) NOT NULL,
    `timestamp` DATETIME NOT NULL,
    `device_sn` VARCHAR(150) NOT NULL,
    `template_hash` VARCHAR(255) NULL,
    `client_event_id` VARCHAR(100) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_presensi_client_event_id` (`client_event_id`),
    KEY `idx_presensi_user_id` (`user_id`),
    KEY `idx_presensi_timestamp` (`timestamp`),
    KEY `idx_presensi_created_at` (`created_at`),
    KEY `idx_presensi_client_event_id` (`client_event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `employees` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_code` VARCHAR(100) NOT NULL,
    `nip` VARCHAR(50) NULL,
    `nik` VARCHAR(50) NULL,
    `name` VARCHAR(150) NOT NULL,
    `birth_place` VARCHAR(100) NULL,
    `birth_date` DATE NULL,
    `gender` ENUM('M','F') NULL,
    `address` TEXT NULL,
    `phone` VARCHAR(30) NULL,
    `email` VARCHAR(150) NULL,
    `join_date` DATE NULL,
    `exit_date` DATE NULL,
    `position_name` VARCHAR(100) NULL,
    `department_name` VARCHAR(100) NULL,
    `employment_status` ENUM('TETAP','KONTRAK','HARIAN','NONAKTIF') NOT NULL DEFAULT 'TETAP',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `bank_account_number` VARCHAR(100) NULL,
    `bank_name` VARCHAR(100) NULL,
    `base_salary` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `payroll_status` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_employees_code` (`employee_code`),
    UNIQUE KEY `uq_employees_nip` (`nip`),
    UNIQUE KEY `uq_employees_nik` (`nik`),
    KEY `idx_employees_active` (`is_active`),
    KEY `idx_employees_department` (`department_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `employee_fingerprint_mappings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `fingerprint_user_id` VARCHAR(100) NOT NULL,
    `device_sn` VARCHAR(150) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_fingerprint_device_user` (`fingerprint_user_id`, `device_sn`),
    KEY `idx_mapping_employee` (`employee_id`),
    CONSTRAINT `fk_mapping_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE IF NOT EXISTS `work_shifts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `check_in_time` TIME NOT NULL,
    `check_out_time` TIME NOT NULL,
    `late_tolerance_minutes` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `minimum_overtime_minutes` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `early_leave_enabled` TINYINT(1) NOT NULL DEFAULT 1,
    `work_days` VARCHAR(20) NOT NULL DEFAULT '1,2,3,4,5',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_shifts_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `employee_shift_assignments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `shift_id` BIGINT UNSIGNED NOT NULL,
    `effective_from` DATE NOT NULL,
    `effective_until` DATE NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_assignment_employee_start` (`employee_id`, `effective_from`),
    KEY `idx_assignment_employee_date` (`employee_id`, `effective_from`, `effective_until`),
    CONSTRAINT `fk_assignment_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
    CONSTRAINT `fk_assignment_shift` FOREIGN KEY (`shift_id`) REFERENCES `work_shifts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `attendance_daily` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `attendance_date` DATE NOT NULL,
    `shift_id` BIGINT UNSIGNED NULL,
    `check_in` DATETIME NULL,
    `check_out` DATETIME NULL,
    `late_minutes` INT UNSIGNED NOT NULL DEFAULT 0,
    `early_leave_minutes` INT UNSIGNED NOT NULL DEFAULT 0,
    `overtime_minutes` INT UNSIGNED NOT NULL DEFAULT 0,
    `attendance_status` ENUM('HADIR','TERLAMBAT','IZIN','SAKIT','DINAS','CUTI','ALPA','LIBUR','OFF','TIDAK_LENGKAP') NOT NULL DEFAULT 'TIDAK_LENGKAP',
    `source` VARCHAR(30) NOT NULL DEFAULT 'FINGERPRINT',
    `processed_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_attendance_employee_date` (`employee_id`, `attendance_date`),
    KEY `idx_attendance_date` (`attendance_date`),
    KEY `idx_attendance_status` (`attendance_status`),
    CONSTRAINT `fk_attendance_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
    CONSTRAINT `fk_attendance_shift` FOREIGN KEY (`shift_id`) REFERENCES `work_shifts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tbl_user` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nip` VARCHAR(40) NULL,
    `usernm` VARCHAR(40) NOT NULL,
    `nama` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NULL,
    `jk` TINYINT NULL,
    `pass` VARCHAR(255) NOT NULL,
    `notelp` VARCHAR(30) NULL,
    `alamat` VARCHAR(255) NULL,
    `level` TINYINT NOT NULL DEFAULT 0,
    `role` ENUM('ADMIN') NOT NULL DEFAULT 'ADMIN',
    `sts_app` TINYINT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_tbl_user_usernm` (`usernm`),
    KEY `idx_tbl_user_level` (`level`),
    KEY `idx_tbl_user_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `attendance_adjustments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `attendance_date` DATE NOT NULL,
    `adjustment_type` ENUM('SAKIT','IZIN','DINAS','TUGAS_LUAR','LUPA_MASUK','LUPA_PULANG','MASALAH_FINGERPRINT','LAINNYA') NOT NULL,
    `check_in` DATETIME NULL,
    `check_out` DATETIME NULL,
    `reason` TEXT NOT NULL,
    `attachment` VARCHAR(255) NULL,
    `status` ENUM('DRAFT','SUBMITTED','NEED_REVISION','APPROVED','REJECTED','CANCELLED') NOT NULL DEFAULT 'DRAFT',
    `submitted_by` INT NULL,
    `approved_by` INT NULL,
    `approved_at` DATETIME NULL,
    `rejection_reason` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_adjustment_employee_date` (`employee_id`, `attendance_date`),
    KEY `idx_adjustment_status` (`status`),
    CONSTRAINT `fk_adjustment_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `payroll_components` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `component_type` ENUM('EARNING','DEDUCTION') NOT NULL,
    `calculation_type` ENUM('FIXED','PERCENTAGE','PER_MINUTE','RANGE') NOT NULL DEFAULT 'FIXED',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_payroll_component_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `payroll_components` (`code`, `name`, `component_type`, `calculation_type`) VALUES
('GAJI_POKOK', 'Gaji Pokok', 'EARNING', 'FIXED'),
('TUNJANGAN_A', 'Tunjangan A', 'EARNING', 'FIXED'),
('TUNJANGAN_LEMBUR', 'Tunjangan Lembur', 'EARNING', 'FIXED'),
('TUNJANGAN_JABATAN', 'Tunjangan Jabatan', 'EARNING', 'FIXED'),
('TUNJANGAN_BPJS', 'Tunjangan BPJS', 'EARNING', 'FIXED'),
('POTONGAN_PAJAK', 'Potongan Pajak', 'DEDUCTION', 'FIXED'),
('POTONGAN_KETERLAMBATAN', 'Potongan Keterlambatan', 'DEDUCTION', 'PER_MINUTE'),
('POTONGAN_KASBON', 'Potongan Kasbon', 'DEDUCTION', 'FIXED');

CREATE TABLE IF NOT EXISTS `employee_payroll_components` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `component_id` BIGINT UNSIGNED NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `effective_from` DATE NOT NULL,
    `effective_until` DATE NULL,
    PRIMARY KEY (`id`),
    KEY `idx_employee_component` (`employee_id`, `component_id`),
    CONSTRAINT `fk_epc_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
    CONSTRAINT `fk_epc_component` FOREIGN KEY (`component_id`) REFERENCES `payroll_components` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `payroll_periods` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `period_start` DATE NOT NULL,
    `period_end` DATE NOT NULL,
    `status` ENUM('DRAFT','CALCULATING','REVIEW','FINALIZED','CANCELLED') NOT NULL DEFAULT 'DRAFT',
    `calculated_at` DATETIME NULL,
    `finalized_at` DATETIME NULL,
    `finalized_by` INT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_payroll_period_dates` (`period_start`, `period_end`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `payroll_details` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `period_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `gross_salary` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `total_deduction` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `take_home_pay` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `calculation_detail` JSON NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_payroll_detail_employee` (`period_id`, `employee_id`),
    CONSTRAINT `fk_payroll_detail_period` FOREIGN KEY (`period_id`) REFERENCES `payroll_periods` (`id`),
    CONSTRAINT `fk_payroll_detail_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `loan_applications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `installment_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `reason` TEXT NULL,
    `status` ENUM('DRAFT','SUBMITTED','APPROVED','REJECTED','CANCELLED') NOT NULL DEFAULT 'DRAFT',
    `approved_by` INT NULL,
    `approved_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_loan_application_employee` (`employee_id`, `status`),
    CONSTRAINT `fk_loan_application_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `loan_ledger` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `loan_application_id` BIGINT UNSIGNED NULL,
    `transaction_date` DATE NOT NULL,
    `transaction_type` ENUM('KASBON','PEMBAYARAN','POTONGAN_PAYROLL','KOREKSI','PELUNASAN') NOT NULL,
    `debit` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `credit` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `reference` VARCHAR(100) NULL,
    `notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_loan_ledger_employee_date` (`employee_id`, `transaction_date`),
    CONSTRAINT `fk_loan_ledger_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
    CONSTRAINT `fk_loan_ledger_application` FOREIGN KEY (`loan_application_id`) REFERENCES `loan_applications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT NULL,
    `action` VARCHAR(50) NOT NULL,
    `module` VARCHAR(50) NOT NULL,
    `record_id` VARCHAR(100) NULL,
    `old_value` JSON NULL,
    `new_value` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_audit_module_record` (`module`, `record_id`),
    KEY `idx_audit_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `api_tokens` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `token_prefix` VARCHAR(16) NOT NULL,
    `token_hash` CHAR(64) NOT NULL,
    `expires_at` DATETIME NULL,
    `revoked_at` DATETIME NULL,
    `last_used_at` DATETIME NULL,
    `created_by` INT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_api_tokens_hash` (`token_hash`),
    KEY `idx_api_tokens_active` (`revoked_at`, `expires_at`),
    CONSTRAINT `fk_api_tokens_created_by` FOREIGN KEY (`created_by`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
