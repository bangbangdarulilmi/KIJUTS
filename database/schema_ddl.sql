-- =====================================================================
-- DDL Schema: Web Portal HMIF STT Cipasung
-- Version: 1.1
-- Target Engine: MySQL / MariaDB (XAMPP Compatible)
-- Character Set: utf8mb4 / Collation: utf8mb4_unicode_ci
-- Note: Skema bersih tanpa dummy data / seeder bawaan.
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- 1. Table: users
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `nim` VARCHAR(30) NULL DEFAULT NULL,
  `role` VARCHAR(30) NOT NULL DEFAULT 'mahasiswa' COMMENT 'super_admin, pengurus, mahasiswa',
  `avatar` VARCHAR(255) NULL DEFAULT NULL,
  `phone` VARCHAR(25) NULL DEFAULT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'active, pending, inactive',
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_nim_unique` (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 2. Table: password_reset_tokens
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 3. Table: sessions
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `user_agent` TEXT NULL DEFAULT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 4. Table: members (Manajemen Anggota, Alumni & Talent Directory)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `nim` VARCHAR(30) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `generation` VARCHAR(10) NOT NULL COMMENT 'Angkatan masuk, misal 2022',
  `status` ENUM('active', 'alumni', 'inactive') NOT NULL DEFAULT 'active',
  `role_in_org` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Jabatan struktural himpunan',
  `bio` TEXT NULL DEFAULT NULL,
  `skills` TEXT NULL DEFAULT NULL COMMENT 'JSON atau list keahlian',
  `linkedin_url` VARCHAR(255) NULL DEFAULT NULL,
  `github_url` VARCHAR(255) NULL DEFAULT NULL,
  `portfolio_url` VARCHAR(255) NULL DEFAULT NULL,
  `avatar` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `members_nim_unique` (`nim`),
  KEY `members_user_id_foreign` (`user_id`),
  CONSTRAINT `members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 5. Table: events (Agenda & Event Kegiatan HMIF)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `description` LONGTEXT NOT NULL,
  `category` VARCHAR(50) NOT NULL DEFAULT 'workshop',
  `banner` VARCHAR(255) NULL DEFAULT NULL,
  `event_date` DATETIME NOT NULL,
  `end_date` DATETIME NULL DEFAULT NULL,
  `location` VARCHAR(255) NOT NULL,
  `is_online` TINYINT(1) NOT NULL DEFAULT 0,
  `quota` INT UNSIGNED NULL DEFAULT NULL,
  `status` ENUM('upcoming', 'ongoing', 'completed', 'cancelled') NOT NULL DEFAULT 'upcoming',
  `qr_presensi_token` VARCHAR(64) NULL DEFAULT NULL,
  `presensi_open` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_slug_unique` (`slug`),
  UNIQUE KEY `events_qr_presensi_token_unique` (`qr_presensi_token`),
  KEY `events_created_by_foreign` (`created_by`),
  CONSTRAINT `events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 6. Table: event_registrations (Pendaftaran Event, Presensi QR, & E-Sertifikat)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `event_registrations`;
CREATE TABLE `event_registrations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `registration_code` VARCHAR(40) NOT NULL,
  `attendance_status` ENUM('registered', 'present', 'absent') NOT NULL DEFAULT 'registered',
  `attended_at` TIMESTAMP NULL DEFAULT NULL,
  `certificate_code` VARCHAR(50) NULL DEFAULT NULL,
  `certificate_generated_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_registrations_registration_code_unique` (`registration_code`),
  UNIQUE KEY `event_registrations_certificate_code_unique` (`certificate_code`),
  UNIQUE KEY `event_registrations_event_user_unique` (`event_id`, `user_id`),
  KEY `event_registrations_user_id_foreign` (`user_id`),
  CONSTRAINT `event_registrations_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_registrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 7. Table: categories (Kategori Artikel & Berita)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 8. Table: articles (CMS Berita, Pengumuman & Artikel Teknologi)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `author_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `excerpt` TEXT NULL DEFAULT NULL,
  `content` LONGTEXT NOT NULL,
  `cover_image` VARCHAR(255) NULL DEFAULT NULL,
  `status` ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft',
  `published_at` TIMESTAMP NULL DEFAULT NULL,
  `views` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articles_slug_unique` (`slug`),
  KEY `articles_category_id_foreign` (`category_id`),
  KEY `articles_author_id_foreign` (`author_id`),
  CONSTRAINT `articles_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `articles_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 9. Table: inventories (Aset & Inventaris HMIF)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `inventories`;
CREATE TABLE `inventories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_code` VARCHAR(50) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `category` VARCHAR(50) NOT NULL DEFAULT 'Elektronik',
  `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `condition` ENUM('good', 'needs_repair', 'damaged') NOT NULL DEFAULT 'good',
  `location` VARCHAR(100) NOT NULL DEFAULT 'Sekretariat HMIF',
  `image` VARCHAR(255) NULL DEFAULT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventories_item_code_unique` (`item_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 10. Table: inventory_loans (Peminjaman Inventaris Himpunan)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `inventory_loans`;
CREATE TABLE `inventory_loans` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `inventory_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `borrower_name` VARCHAR(255) NOT NULL,
  `borrower_contact` VARCHAR(30) NOT NULL,
  `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `loan_date` DATE NOT NULL,
  `expected_return_date` DATE NOT NULL,
  `actual_return_date` DATE NULL DEFAULT NULL,
  `status` ENUM('pending', 'approved', 'returned', 'rejected', 'overdue') NOT NULL DEFAULT 'pending',
  `approved_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_loans_inventory_id_foreign` (`inventory_id`),
  KEY `inventory_loans_user_id_foreign` (`user_id`),
  KEY `inventory_loans_approved_by_foreign` (`approved_by`),
  CONSTRAINT `inventory_loans_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_loans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_loans_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 11. Table: academic_repositories (Bank Soal & Materi Kuliah)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `academic_repositories`;
CREATE TABLE `academic_repositories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uploader_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `type` ENUM('bank_soal', 'modul_praktikum', 'source_code', 'catatan_kuliah') NOT NULL DEFAULT 'bank_soal',
  `course_name` VARCHAR(150) NOT NULL,
  `semester` TINYINT UNSIGNED NOT NULL,
  `academic_year` VARCHAR(20) NULL DEFAULT NULL,
  `exam_type` ENUM('uts', 'uas', 'kuis', 'tugas', 'other') NULL DEFAULT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_size` VARCHAR(50) NULL DEFAULT NULL,
  `file_extension` VARCHAR(20) NULL DEFAULT NULL,
  `download_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `description` TEXT NULL DEFAULT NULL,
  `is_verified` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academic_repositories_uploader_id_foreign` (`uploader_id`),
  CONSTRAINT `academic_repositories_uploader_id_foreign` FOREIGN KEY (`uploader_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 12. Table: coding_clinics (Dev-Helpdesk Klinik Koding)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `coding_clinics`;
CREATE TABLE `coding_clinics` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `category` VARCHAR(50) NOT NULL DEFAULT 'bug_error',
  `programming_language` VARCHAR(50) NULL DEFAULT NULL,
  `description` LONGTEXT NOT NULL,
  `code_snippet` LONGTEXT NULL DEFAULT NULL,
  `screenshot` VARCHAR(255) NULL DEFAULT NULL,
  `status` ENUM('open', 'in_progress', 'resolved', 'closed') NOT NULL DEFAULT 'open',
  `mentor_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `solution_notes` LONGTEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coding_clinics_user_id_foreign` (`user_id`),
  KEY `coding_clinics_mentor_id_foreign` (`mentor_id`),
  CONSTRAINT `coding_clinics_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `coding_clinics_mentor_id_foreign` FOREIGN KEY (`mentor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 13. Table: coding_clinic_replies (Tanggapan Diskusi / Peer-Tutoring)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `coding_clinic_replies`;
CREATE TABLE `coding_clinic_replies` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `clinic_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `reply` LONGTEXT NOT NULL,
  `code_snippet` LONGTEXT NULL DEFAULT NULL,
  `is_solution` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coding_clinic_replies_clinic_id_foreign` (`clinic_id`),
  KEY `coding_clinic_replies_user_id_foreign` (`user_id`),
  CONSTRAINT `coding_clinic_replies_clinic_id_foreign` FOREIGN KEY (`clinic_id`) REFERENCES `coding_clinics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `coding_clinic_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 14. Table: cash_transactions (Transparansi Kas Himpunan)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `cash_transactions`;
CREATE TABLE `cash_transactions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `recorded_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `type` ENUM('income', 'expense') NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `amount` DECIMAL(14, 2) NOT NULL,
  `transaction_date` DATE NOT NULL,
  `proof_image` VARCHAR(255) NULL DEFAULT NULL,
  `description` TEXT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cash_transactions_recorded_by_foreign` (`recorded_by`),
  CONSTRAINT `cash_transactions_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 15. Table: student_projects (Showcase Galeri Karya Mahasiswa)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `student_projects`;
CREATE TABLE `student_projects` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `short_description` VARCHAR(255) NOT NULL,
  `description` LONGTEXT NOT NULL,
  `tech_stack` VARCHAR(255) NOT NULL,
  `demo_url` VARCHAR(255) NULL DEFAULT NULL,
  `repo_url` VARCHAR(255) NULL DEFAULT NULL,
  `thumbnail` VARCHAR(255) NULL DEFAULT NULL,
  `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_projects_slug_unique` (`slug`),
  KEY `student_projects_user_id_foreign` (`user_id`),
  CONSTRAINT `student_projects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 16. Table: aspirations (Aspirasi & Form Pesan Publik)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `aspirations`;
CREATE TABLE `aspirations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `sender_name` VARCHAR(100) NOT NULL,
  `sender_email` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `category` ENUM('fasilitas', 'akademik', 'kegiatan_himpunan', 'kritik_saran', 'lainnya') NOT NULL DEFAULT 'kritik_saran',
  `is_public` TINYINT(1) NOT NULL DEFAULT 0,
  `status` ENUM('unread', 'read', 'responded') NOT NULL DEFAULT 'unread',
  `response` TEXT NULL DEFAULT NULL,
  `responded_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aspirations_user_id_foreign` (`user_id`),
  KEY `aspirations_responded_by_foreign` (`responded_by`),
  CONSTRAINT `aspirations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `aspirations_responded_by_foreign` FOREIGN KEY (`responded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
