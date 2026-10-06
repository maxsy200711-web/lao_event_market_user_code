-- One-time conversion from organizer_users to direct Organizer credentials.
-- Review for duplicate linked accounts before running on another database.

ALTER TABLE organizers
  ADD COLUMN username VARCHAR(100) NULL AFTER organizer_id,
  ADD COLUMN password VARBINARY(255) NULL AFTER email;

-- Move existing login names and password hashes into the Organizer records.
UPDATE organizers o
JOIN organizer_users ou ON ou.organizer_id = o.organizer_id
JOIN users u ON u.user_id = ou.user_id
SET o.username = u.username,
    o.password = u.password;

ALTER TABLE organizers
  MODIFY username VARCHAR(100) NOT NULL,
  MODIFY password VARBINARY(255) NOT NULL,
  ADD UNIQUE KEY uq_organizers_username (username);

-- New applications hold credentials in the request queue without a users row.
ALTER TABLE organizer_requests
  MODIFY user_id INT NULL,
  ADD COLUMN username VARCHAR(100) NULL AFTER user_id,
  ADD COLUMN email VARCHAR(100) NULL AFTER username,
  ADD COLUMN password VARBINARY(255) NULL AFTER email;

-- Preserve request credentials from the previous user-based workflow.
UPDATE organizer_requests r
JOIN users u ON u.user_id = r.user_id
SET r.username = u.username,
    r.email = u.email,
    r.password = u.password
WHERE r.username IS NULL;

-- Organizer applications are independent of normal user accounts.
ALTER TABLE organizer_requests
  DROP INDEX idx_organizer_requests_user,
  DROP COLUMN user_id;

DROP TABLE organizer_users;
DROP TABLE IF EXISTS organizer_accounts;
DROP TABLE IF EXISTS organizer_users_backup_20261002;
