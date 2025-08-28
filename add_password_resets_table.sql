-- Add password_resets table for password reset functionality
-- Run this SQL to add the required table to your database

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `used_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`),
  KEY `expires_at` (`expires_at`),
  KEY `used` (`used`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add index for better performance
CREATE INDEX `idx_password_resets_token_expires` ON `password_resets` (`token`, `expires_at`);
CREATE INDEX `idx_password_resets_user_expires` ON `password_resets` (`user_id`, `expires_at`);

-- Optional: Clean up old expired tokens (run this periodically)
-- DELETE FROM password_resets WHERE expires_at < NOW() AND used = 0;








