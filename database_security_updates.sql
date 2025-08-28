-- DreamLock Database Security Updates
-- Run these SQL commands to enhance database security

-- Add missing columns to users table
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS phone VARCHAR(20) NULL,
ADD COLUMN IF NOT EXISTS preferred_language VARCHAR(10) DEFAULT 'en',
ADD COLUMN IF NOT EXISTS failed_login_attempts INT DEFAULT 0,
ADD COLUMN IF NOT EXISTS last_login_at TIMESTAMP NULL,
ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS is_active BOOLEAN DEFAULT TRUE,
ADD COLUMN IF NOT EXISTS email_verified BOOLEAN DEFAULT FALSE,
ADD COLUMN IF NOT EXISTS phone_verified BOOLEAN DEFAULT FALSE;

-- Add missing columns to dreams table
ALTER TABLE dreams 
ADD COLUMN IF NOT EXISTS sharing_enabled BOOLEAN DEFAULT FALSE,
ADD COLUMN IF NOT EXISTS is_public BOOLEAN DEFAULT FALSE,
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Create security audit log table
CREATE TABLE IF NOT EXISTS security_audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    event_type VARCHAR(50) NOT NULL,
    event_details JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_event_type (event_type),
    INDEX idx_created_at (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Create remember tokens table for secure "remember me" functionality
CREATE TABLE IF NOT EXISTS remember_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (token),
    INDEX idx_user_id (user_id),
    INDEX idx_expires_at (expires_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create password reset tokens table
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (token),
    INDEX idx_user_id (user_id),
    INDEX idx_expires_at (expires_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create email verification tokens table
CREATE TABLE IF NOT EXISTS email_verification_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (token),
    INDEX idx_user_id (user_id),
    INDEX idx_expires_at (expires_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Add indexes for better performance and security
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_email (email);
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_username (username);
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_phone (phone);
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_is_active (is_active);

ALTER TABLE dreams ADD INDEX IF NOT EXISTS idx_user_id_created (user_id, created_at);
ALTER TABLE dreams ADD INDEX IF NOT EXISTS idx_dream_type (dream_type);
ALTER TABLE dreams ADD INDEX IF NOT EXISTS idx_is_public (is_public);

-- Create a view for user statistics (for admin use)
CREATE OR REPLACE VIEW user_statistics AS
SELECT 
    u.id,
    u.username,
    u.email,
    u.is_premium,
    u.created_at,
    u.last_login_at,
    COUNT(d.id) as total_dreams,
    MAX(d.created_at) as last_dream_date
FROM users u
LEFT JOIN dreams d ON u.id = d.user_id
WHERE u.is_active = TRUE
GROUP BY u.id;

-- Create stored procedure for secure user cleanup
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS cleanup_inactive_users()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE user_id INT;
    DECLARE user_cursor CURSOR FOR 
        SELECT id FROM users 
        WHERE is_active = FALSE 
        AND updated_at < DATE_SUB(NOW(), INTERVAL 1 YEAR);
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN user_cursor;
    
    read_loop: LOOP
        FETCH user_cursor INTO user_id;
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        -- Delete user's dreams
        DELETE FROM dreams WHERE user_id = user_id;
        
        -- Delete user's tokens
        DELETE FROM remember_tokens WHERE user_id = user_id;
        DELETE FROM password_reset_tokens WHERE user_id = user_id;
        DELETE FROM email_verification_tokens WHERE user_id = user_id;
        
        -- Delete user
        DELETE FROM users WHERE id = user_id;
        
    END LOOP;
    
    CLOSE user_cursor;
END //
DELIMITER ;

-- Create stored procedure for secure password update
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS update_user_password(
    IN p_user_id INT,
    IN p_new_password_hash VARCHAR(255)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Password update failed';
    END;
    
    START TRANSACTION;
    
    -- Update password
    UPDATE users 
    SET password = p_new_password_hash, 
        updated_at = NOW() 
    WHERE id = p_user_id AND is_active = TRUE;
    
    -- Invalidate all remember tokens for this user
    DELETE FROM remember_tokens WHERE user_id = p_user_id;
    
    -- Log the password change
    INSERT INTO security_audit_log (user_id, event_type, event_details, ip_address)
    VALUES (p_user_id, 'password_changed', '{"timestamp": NOW()}', 'SYSTEM');
    
    COMMIT;
END //
DELIMITER ;

-- Create event to clean up expired tokens daily
CREATE EVENT IF NOT EXISTS cleanup_expired_tokens
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_TIMESTAMP
DO
BEGIN
    DELETE FROM remember_tokens WHERE expires_at < NOW();
    DELETE FROM password_reset_tokens WHERE expires_at < NOW();
    DELETE FROM email_verification_tokens WHERE expires_at < NOW();
END;

-- Create event to clean up old audit logs (keep last 6 months)
CREATE EVENT IF NOT EXISTS cleanup_old_audit_logs
ON SCHEDULE EVERY 1 WEEK
STARTS CURRENT_TIMESTAMP
DO
BEGIN
    DELETE FROM security_audit_log 
    WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH);
END;

-- Grant minimal permissions to application user (if using separate DB user)
-- Replace 'dreamlock_user' with your actual database username
-- GRANT SELECT, INSERT, UPDATE, DELETE ON dreamlock.* TO 'dreamlock_user'@'localhost';
-- GRANT EXECUTE ON PROCEDURE dreamlock.update_user_password TO 'dreamlock_user'@'localhost';
-- GRANT EXECUTE ON PROCEDURE dreamlock.cleanup_inactive_users TO 'dreamlock_user'@'localhost';

-- Update existing users to have proper timestamps
UPDATE users SET created_at = NOW() WHERE created_at IS NULL;
UPDATE users SET updated_at = NOW() WHERE updated_at IS NULL;

-- Set all existing users as active
UPDATE users SET is_active = TRUE WHERE is_active IS NULL;

-- Add comments for documentation
ALTER TABLE users COMMENT = 'User accounts with enhanced security features';
ALTER TABLE dreams COMMENT = 'User dreams with privacy controls';
ALTER TABLE security_audit_log COMMENT = 'Security event logging for audit trail';
ALTER TABLE remember_tokens COMMENT = 'Secure remember me tokens';
ALTER TABLE password_reset_tokens COMMENT = 'Password reset tokens with expiration';
ALTER TABLE email_verification_tokens COMMENT = 'Email verification tokens';

-- Create trigger to log user creation
DELIMITER //
CREATE TRIGGER IF NOT EXISTS log_user_creation
AFTER INSERT ON users
FOR EACH ROW
BEGIN
    INSERT INTO security_audit_log (user_id, event_type, event_details, ip_address)
    VALUES (NEW.id, 'user_created', 
            JSON_OBJECT('username', NEW.username, 'email', NEW.email, 'timestamp', NOW()),
            'SYSTEM');
END //
DELIMITER ;

-- Create trigger to log user login
DELIMITER //
CREATE TRIGGER IF NOT EXISTS log_user_login
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    IF NEW.last_login_at != OLD.last_login_at THEN
        INSERT INTO security_audit_log (user_id, event_type, event_details, ip_address)
        VALUES (NEW.id, 'user_login', 
                JSON_OBJECT('username', NEW.username, 'timestamp', NOW()),
                'SYSTEM');
    END IF;
END //
DELIMITER ;

-- Show summary of security updates
SELECT 'Database security updates completed successfully' as status;
SELECT COUNT(*) as total_users FROM users;
SELECT COUNT(*) as total_dreams FROM dreams;
SELECT 'Security audit log table created' as audit_log_status;
SELECT 'Remember tokens table created' as remember_tokens_status;
SELECT 'Password reset tokens table created' as password_reset_status;
SELECT 'Email verification tokens table created' as email_verification_status;

