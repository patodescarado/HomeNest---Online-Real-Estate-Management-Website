SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS moderation_logs, notifications, reports, appointments,
    favorites, property_images, properties, property_types, districts,
    provinces, user_profiles, users;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    user_id        BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email          VARCHAR(255) NOT NULL UNIQUE,
    password_hash  VARCHAR(255) NOT NULL,              -- bcrypt, do ứng dụng băm
    role           VARCHAR(10)  NOT NULL DEFAULT 'user',
    is_locked      BOOLEAN      NOT NULL DEFAULT FALSE,
    created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_users_role CHECK (role IN ('user', 'admin'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE user_profiles (                           -- quan hệ 1-1 với users
    user_id    BIGINT UNSIGNED PRIMARY KEY,
    full_name  VARCHAR(150) NOT NULL,
    phone      VARCHAR(20),
    avatar     VARCHAR(255),
    bio        TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE provinces (
    province_id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE districts (
    district_id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    province_id  INT UNSIGNED NOT NULL,
    name         VARCHAR(100) NOT NULL,
    UNIQUE (province_id, name),
    FOREIGN KEY (province_id) REFERENCES provinces(province_id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE property_types (                          -- cũng là nguồn dữ liệu cho menu xổ xuống
    type_id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL UNIQUE,
    slug        VARCHAR(100) NOT NULL UNIQUE,          -- dùng cho URL: /mua-ban/nha-rieng
    for_sale    BOOLEAN  NOT NULL DEFAULT TRUE,        -- hiện trong menu "Mua bán"
    for_rent    BOOLEAN  NOT NULL DEFAULT TRUE,        -- hiện trong menu "Cho thuê"
    sort_order  SMALLINT NOT NULL DEFAULT 0,           -- thứ tự trong menu
    CONSTRAINT chk_type_kind CHECK (for_sale OR for_rent)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE properties (
    property_id   BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    owner_id      BIGINT UNSIGNED NOT NULL,
    type_id       INT UNSIGNED    NOT NULL,
    district_id   INT UNSIGNED    NOT NULL,
    title         VARCHAR(255)    NOT NULL,
    description   TEXT,
    listing_kind  VARCHAR(4)      NOT NULL,                  -- sale | rent
    price         DECIMAL(15,0)   NOT NULL,                  -- đơn vị: VND
    area          DECIMAL(10,2)   NOT NULL,                  -- đơn vị: m2
    bedrooms      SMALLINT        NOT NULL DEFAULT 0,
    bathrooms     SMALLINT        NOT NULL DEFAULT 0,
    address       VARCHAR(255)    NOT NULL,
    latitude      DECIMAL(9,6),
    longitude     DECIMAL(9,6),
    status        VARCHAR(10)     NOT NULL DEFAULT 'pending',
    view_count    INT UNSIGNED    NOT NULL DEFAULT 0,
    expires_at    DATETIME,
    created_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_prop_kind   CHECK (listing_kind IN ('sale', 'rent')),
    CONSTRAINT chk_prop_status CHECK (status IN ('draft','pending','approved','rejected','expired','hidden')),
    CONSTRAINT chk_prop_price  CHECK (price > 0),
    CONSTRAINT chk_prop_area   CHECK (area > 0),
    CONSTRAINT chk_prop_rooms  CHECK (bedrooms >= 0 AND bathrooms >= 0),
    CONSTRAINT chk_prop_lat    CHECK (latitude  IS NULL OR latitude  BETWEEN -90  AND 90),
    CONSTRAINT chk_prop_lng    CHECK (longitude IS NULL OR longitude BETWEEN -180 AND 180),
    FOREIGN KEY (owner_id)    REFERENCES users(user_id)          ON DELETE CASCADE,
    FOREIGN KEY (type_id)     REFERENCES property_types(type_id) ON DELETE RESTRICT,
    FOREIGN KEY (district_id) REFERENCES districts(district_id)  ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE property_images (
    image_id     BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    property_id  BIGINT UNSIGNED NOT NULL,
    image_path   VARCHAR(255) NOT NULL,
    is_cover     BOOLEAN  NOT NULL DEFAULT FALSE,
    sort_order   SMALLINT NOT NULL DEFAULT 0,
    -- Mỗi tin chỉ có tối đa 1 ảnh bìa (MySQL không có index một phần,
    cover_key    BIGINT UNSIGNED GENERATED ALWAYS AS (IF(is_cover, property_id, NULL)) VIRTUAL,
    UNIQUE KEY uq_property_cover (cover_key),
    FOREIGN KEY (property_id) REFERENCES properties(property_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Nhóm 4: Tương tác ----------------------------------------------------
CREATE TABLE favorites (
    user_id      BIGINT UNSIGNED NOT NULL,
    property_id  BIGINT UNSIGNED NOT NULL,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, property_id),                  -- không lưu trùng
    FOREIGN KEY (user_id)     REFERENCES users(user_id)          ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(property_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE appointments (
    appointment_id  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    property_id     BIGINT UNSIGNED NOT NULL,
    user_id         BIGINT UNSIGNED NOT NULL,
    scheduled_at    DATETIME NOT NULL,
    note            TEXT,
    status          VARCHAR(10) NOT NULL DEFAULT 'pending',
    CONSTRAINT chk_appt_status CHECK (status IN ('pending','confirmed','cancelled')),
    FOREIGN KEY (property_id) REFERENCES properties(property_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)     REFERENCES users(user_id)          ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reports (
    report_id    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    property_id  BIGINT UNSIGNED NOT NULL,
    reporter_id  BIGINT UNSIGNED NOT NULL,
    reason       TEXT NOT NULL,
    status       VARCHAR(10) NOT NULL DEFAULT 'open',
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_report_status CHECK (status IN ('open','resolved','dismissed')),
    FOREIGN KEY (property_id) REFERENCES properties(property_id) ON DELETE CASCADE,
    FOREIGN KEY (reporter_id) REFERENCES users(user_id)          ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE notifications (
    notification_id  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id          BIGINT UNSIGNED NOT NULL,
    content          TEXT NOT NULL,
    is_read          BOOLEAN NOT NULL DEFAULT FALSE,
    created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Nhóm 5: Quản trị -----------------------------------------------------
CREATE TABLE moderation_logs (
    log_id       BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    property_id  BIGINT UNSIGNED NOT NULL,
    admin_id     BIGINT UNSIGNED NOT NULL,
    action       VARCHAR(10) NOT NULL,
    reason       TEXT,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_log_action CHECK (action IN ('approve','reject')),
    CONSTRAINT chk_log_reason CHECK (action = 'approve' OR reason IS NOT NULL),  -- từ chối bắt buộc có lý do
    FOREIGN KEY (property_id) REFERENCES properties(property_id) ON DELETE CASCADE,
    FOREIGN KEY (admin_id)    REFERENCES users(user_id)          ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- PHẦN 2: INDEX CHO TÌM KIẾM VÀ LỌC
-- (MySQL tự tạo index cho khóa ngoại; bên dưới là index thêm cho truy vấn lọc)
-- =====================================================================
CREATE INDEX idx_prop_status_exp  ON properties(status, expires_at);
CREATE INDEX idx_prop_kind_price  ON properties(listing_kind, price);
CREATE INDEX idx_prop_created     ON properties(created_at);
CREATE INDEX idx_notif_user       ON notifications(user_id, is_read);
CREATE INDEX idx_report_status    ON reports(status);
CREATE INDEX idx_log_property     ON moderation_logs(property_id, created_at);

-- Chặn tin có loại hình không phù hợp với hình thức
-- (ví dụ: "bán phòng trọ", "cho thuê đất"); ứng dụng vẫn nên kiểm tra trước.
-- updated_at đã tự cập nhật nhờ ON UPDATE CURRENT_TIMESTAMP nên không cần trigger.
DELIMITER $$

CREATE TRIGGER trg_properties_type_kind_ins BEFORE INSERT ON properties
FOR EACH ROW
BEGIN
    DECLARE ok TINYINT DEFAULT 1;
    SELECT IF(NEW.listing_kind = 'sale', for_sale, for_rent) INTO ok
    FROM property_types WHERE type_id = NEW.type_id;
    IF ok = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Loai hinh khong dung cho hinh thuc nay';
    END IF;
END$$

CREATE TRIGGER trg_properties_type_kind_upd BEFORE UPDATE ON properties
FOR EACH ROW
BEGIN
    DECLARE ok TINYINT DEFAULT 1;
    SELECT IF(NEW.listing_kind = 'sale', for_sale, for_rent) INTO ok
    FROM property_types WHERE type_id = NEW.type_id;
    IF ok = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Loai hinh khong dung cho hinh thuc nay';
    END IF;
END$$

DELIMITER ;
