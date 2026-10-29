SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Chạy lại từ đầu được
DROP TABLE IF EXISTS moderation_logs, notifications, reports, appointments,
    favorites, property_images, properties, property_types, districts,
    provinces, user_profiles, users;

SET FOREIGN_KEY_CHECKS = 1;


-- PHẦN 1: TẠO BẢNG


-- Nhóm 1: Người dùng
CREATE TABLE users (
    user_id        BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email          VARCHAR(100) NOT NULL UNIQUE,
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

-- Nhóm 2: Danh mục 
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

-- Nhóm 3: Tin đăng 
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
    -- nên dùng cột sinh tự động dạng VIRTUAL: NULL khi không phải ảnh bìa, NULL được phép trùng;
    -- phải là VIRTUAL, không dùng STORED vì STORED không đi cùng ON DELETE CASCADE)
    cover_key    BIGINT UNSIGNED GENERATED ALWAYS AS (IF(is_cover, property_id, NULL)) VIRTUAL,
    UNIQUE KEY uq_property_cover (cover_key),
    FOREIGN KEY (property_id) REFERENCES properties(property_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Nhóm 4: Tương tác 
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

-- Nhóm 5: Quản trị 
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

-- PHẦN 2: INDEX CHO TÌM KIẾM VÀ LỌC
-- (MySQL tự tạo index cho khóa ngoại; bên dưới là index thêm cho truy vấn lọc)

CREATE INDEX idx_prop_status_exp  ON properties(status, expires_at);
CREATE INDEX idx_prop_kind_price  ON properties(listing_kind, price);
CREATE INDEX idx_prop_created     ON properties(created_at);
CREATE INDEX idx_notif_user       ON notifications(user_id, is_read);
CREATE INDEX idx_report_status    ON reports(status);
CREATE INDEX idx_log_property     ON moderation_logs(property_id, created_at);

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

-- PHẦN 3: DỮ LIỆU MẪU

INSERT INTO provinces(name) VALUES ('Hà Nội'), ('TP. Hồ Chí Minh'), ('Đà Nẵng');

INSERT INTO districts(province_id, name) VALUES
    (1, 'Cầu Giấy'), (1, 'Đống Đa'), (1, 'Hoàng Mai'),
    (2, 'Thủ Đức'),  (2, 'Quận 7'),
    (3, 'Hải Châu'), (3, 'Hòa Vang');

INSERT INTO property_types(name, slug, for_sale, for_rent, sort_order) VALUES
    ('Căn hộ chung cư',     'can-ho-chung-cu', TRUE,  TRUE,  1),   -- id 1
    ('Nhà riêng',           'nha-rieng',       TRUE,  TRUE,  2),   -- id 2
    ('Nhà mặt phố',         'nha-mat-pho',     TRUE,  TRUE,  3),   -- id 3
    ('Biệt thự, liền kề',   'biet-thu',        TRUE,  TRUE,  4),   -- id 4
    ('Đất',                 'dat',             TRUE,  FALSE, 5),   -- id 5 (chỉ bán)
    ('Phòng trọ',           'phong-tro',       FALSE, TRUE,  6),   -- id 6 (chỉ thuê)
    ('Văn phòng',           'van-phong',       FALSE, TRUE,  7),   -- id 7 (chỉ thuê)
    ('Mặt bằng, cửa hàng',  'mat-bang',        FALSE, TRUE,  8),   -- id 8 (chỉ thuê)
    ('Bất động sản khác',   'bds-khac',        TRUE,  TRUE,  9);   -- id 9

INSERT INTO users(email, password_hash, role) VALUES
    ('admin@homenest.vn', '$2y$10$GIU_CHO_BCRYPT_HASH_ADMIN', 'admin'),
    ('user1@example.com', '$2y$10$GIU_CHO_BCRYPT_HASH_USER1', 'user'),
    ('user2@example.com', '$2y$10$GIU_CHO_BCRYPT_HASH_USER2', 'user');

INSERT INTO user_profiles(user_id, full_name, phone, bio) VALUES
    (1, 'Quản trị viên', '0900000000', NULL),
    (2, 'Người dùng 1',  '0911111111', 'Chủ nhà tại Hà Nội'),
    (3, 'Người dùng 2',  '0922222222', NULL);

INSERT INTO properties
    (owner_id, type_id, district_id, title, description, listing_kind, price, area,
     bedrooms, bathrooms, address, latitude, longitude, status, expires_at) VALUES
    (2, 1, 1, 'Căn hộ 2 phòng ngủ Cầu Giấy', 'Căn hộ tầng 12, đủ nội thất.',
     'sale', 3200000000, 68, 2, 2, '12 Trần Thái Tông', 21.0301, 105.7900,
     'approved', NOW() + INTERVAL 25 DAY),
    (2, 3, 4, 'Nhà phố 3 tầng Thủ Đức', 'Nhà mới xây, gần trường học.',
     'rent', 18000000, 90, 4, 3, '45 Võ Văn Ngân', 10.8500, 106.7700,
     'approved', NOW() + INTERVAL 10 DAY),
    (3, 5, 7, 'Đất nền mặt đường Hòa Vang', 'Đất thổ cư, sổ đỏ riêng.',
     'sale', 2500000000, 120, 0, 0, 'QL14B, Hòa Vang', 15.9900, 108.0800,
     'pending', NULL),
    (3, 6, 2, 'Phòng trọ có gác Đống Đa', 'Gần ga tàu điện.',
     'rent', 3500000, 25, 1, 1, '8 Tây Sơn', 21.0110, 105.8200,
     'rejected', NULL),
    (2, 1, 5, 'Căn hộ Quận 7 hết hạn', 'Tin đã hết hạn hiển thị.',
     'rent', 12000000, 55, 1, 1, '100 Nguyễn Thị Thập', 10.7300, 106.7200,
     'expired', NOW() - INTERVAL 3 DAY);

INSERT INTO property_images(property_id, image_path, is_cover, sort_order) VALUES
    (1, 'properties/1/a.jpg', TRUE,  0), (1, 'properties/1/b.jpg', FALSE, 1),
    (2, 'properties/2/a.jpg', TRUE,  0),
    (3, 'properties/3/a.jpg', TRUE,  0),
    (4, 'properties/4/a.jpg', TRUE,  0);

INSERT INTO moderation_logs(property_id, admin_id, action, reason) VALUES
    (1, 1, 'approve', NULL),
    (2, 1, 'approve', NULL),
    (4, 1, 'reject',  'Ảnh mờ, thiếu địa chỉ cụ thể. Vui lòng bổ sung.');

INSERT INTO favorites(user_id, property_id) VALUES (3, 1), (3, 2), (2, 2);
INSERT INTO appointments(property_id, user_id, scheduled_at, note)
    VALUES (1, 3, NOW() + INTERVAL 3 DAY, 'Xem buổi chiều');
INSERT INTO reports(property_id, reporter_id, reason)
    VALUES (2, 3, 'Giá không đúng thực tế');
INSERT INTO notifications(user_id, content) VALUES
    (2, 'Tin "Căn hộ 2 phòng ngủ Cầu Giấy" đã được duyệt.'),
    (3, 'Tin "Phòng trọ có gác Đống Đa" bị từ chối: Ảnh mờ, thiếu địa chỉ cụ thể.');

-- =====================================================================
-- PHẦN 4: TRUY VẤN THƯỜNG DÙNG
-- (:ten là tham số do ứng dụng truyền vào; khi thử trong MySQL Workbench /
--  phpMyAdmin hãy thay bằng giá trị thật, ví dụ :id thành 1, NULL nếu bỏ lọc)
-- =====================================================================

-- 4.1 Danh sách tin công khai (đã duyệt, chưa hết hạn), phân trang 12 tin
SELECT p.property_id, p.title, p.listing_kind, p.price, p.area, p.bedrooms,
       d.name AS district, pr.name AS province, i.image_path AS cover
FROM properties p
JOIN districts d   ON d.district_id = p.district_id
JOIN provinces pr  ON pr.province_id = d.province_id
LEFT JOIN property_images i ON i.property_id = p.property_id AND i.is_cover
WHERE p.status = 'approved' AND p.expires_at > NOW()
ORDER BY p.created_at DESC
LIMIT 12 OFFSET 0;

-- 4.2 Tìm kiếm và lọc (tham số NULL = bỏ qua điều kiện đó)
SELECT p.property_id, p.title, p.price, p.area, p.bedrooms, d.name AS district
FROM properties p
JOIN districts d ON d.district_id = p.district_id
WHERE p.status = 'approved' AND p.expires_at > NOW()
  AND (:kind     IS NULL OR p.listing_kind = :kind)              -- sale / rent
  AND (:type_id  IS NULL OR p.type_id      = :type_id)
  AND (:province IS NULL OR d.province_id  = :province)
  AND (:district IS NULL OR p.district_id  = :district)
  AND (:price_min IS NULL OR p.price      >= :price_min)
  AND (:price_max IS NULL OR p.price      <= :price_max)
  AND (:area_min  IS NULL OR p.area       >= :area_min)
  AND (:area_max  IS NULL OR p.area       <= :area_max)
  AND (:bedrooms  IS NULL OR p.bedrooms   >= :bedrooms)
  AND (:keyword   IS NULL OR p.title       LIKE CONCAT('%', :keyword, '%')
                          OR p.address     LIKE CONCAT('%', :keyword, '%')
                          OR p.description LIKE CONCAT('%', :keyword, '%'))
ORDER BY p.price ASC             -- hoặc p.created_at DESC
LIMIT 12 OFFSET 0;

-- 4.3 Chi tiết một tin + thông tin liên hệ, và tăng lượt xem
SELECT p.*, d.name AS district, pr.name AS province, t.name AS type_name,
       u.full_name AS owner_name, u.phone AS owner_phone
FROM properties p
JOIN districts d      ON d.district_id = p.district_id
JOIN provinces pr     ON pr.province_id = d.province_id
JOIN property_types t ON t.type_id = p.type_id
JOIN user_profiles u  ON u.user_id = p.owner_id
WHERE p.property_id = :id;

SELECT image_path, is_cover FROM property_images
WHERE property_id = :id ORDER BY is_cover DESC, sort_order;

UPDATE properties SET view_count = view_count + 1 WHERE property_id = :id;

-- 4.4 Danh sách quận theo tỉnh (cho ô chọn phụ thuộc)
SELECT district_id, name FROM districts WHERE province_id = :province ORDER BY name;

-- 4.4b Hai menu xổ xuống trên navbar (cũng dùng cho ô "Loại hình" trong form đăng tin)
SELECT name, slug FROM property_types WHERE for_sale ORDER BY sort_order;   -- menu Mua bán
SELECT name, slug FROM property_types WHERE for_rent ORDER BY sort_order;   -- menu Cho thuê

-- 4.4c Trang /mua-ban/{slug} hoặc /cho-thue/{slug}
--      :kind = 'sale' | 'rent', :slug = slug (NULL = tất cả loại hình)
SELECT p.property_id, p.title, p.price, p.area, p.bedrooms, d.name AS district
FROM properties p
JOIN districts d       ON d.district_id = p.district_id
JOIN property_types t  ON t.type_id = p.type_id
WHERE p.status = 'approved' AND p.expires_at > NOW()
  AND p.listing_kind = :kind
  AND (:slug IS NULL OR t.slug = :slug)
ORDER BY p.created_at DESC
LIMIT 12 OFFSET 0;

-- 4.5 "Tin của tôi" kèm lý do từ chối mới nhất
SELECT p.property_id, p.title, p.status, p.expires_at,
       (SELECT l.reason FROM moderation_logs l
        WHERE l.property_id = p.property_id AND l.action = 'reject'
        ORDER BY l.created_at DESC LIMIT 1) AS reject_reason
FROM properties p
WHERE p.owner_id = :user_id
ORDER BY p.created_at DESC;

-- 4.6 Gửi lại tin bị từ chối sau khi sửa
UPDATE properties SET status = 'pending'
WHERE property_id = :id AND owner_id = :user_id AND status = 'rejected';

-- 4.7 Ẩn / hiện lại tin (chỉ hiện lại khi chưa hết hạn)
UPDATE properties SET status = 'hidden'
WHERE property_id = :id AND owner_id = :user_id AND status = 'approved';

UPDATE properties SET status = 'approved'
WHERE property_id = :id AND owner_id = :user_id AND status = 'hidden' AND expires_at > NOW();

-- 4.8 Gia hạn thêm 30 ngày (tính từ hạn cũ nếu còn, từ hôm nay nếu đã hết)
UPDATE properties
SET expires_at = GREATEST(COALESCE(expires_at, NOW()), NOW()) + INTERVAL 30 DAY,
    status = 'approved'
WHERE property_id = :id AND owner_id = :user_id AND status IN ('approved', 'expired');

-- 4.9 Yêu thích: thêm, bỏ, danh sách
INSERT IGNORE INTO favorites(user_id, property_id) VALUES (:user_id, :id);
DELETE FROM favorites WHERE user_id = :user_id AND property_id = :id;

SELECT p.property_id, p.title, p.price, f.created_at AS saved_at
FROM favorites f JOIN properties p ON p.property_id = f.property_id
WHERE f.user_id = :user_id AND p.status = 'approved' AND p.expires_at > NOW()
ORDER BY f.created_at DESC;

-- 4.10 Admin: danh sách tin chờ duyệt (cũ nhất trước)
SELECT p.property_id, p.title, up.full_name AS owner, p.created_at
FROM properties p JOIN user_profiles up ON up.user_id = p.owner_id
WHERE p.status = 'pending'
ORDER BY p.created_at ASC;

-- 4.11 Admin: DUYỆT tin (một transaction: đổi trạng thái + ghi log + thông báo)
START TRANSACTION;
    UPDATE properties
    SET status = 'approved', expires_at = NOW() + INTERVAL 30 DAY
    WHERE property_id = :id AND status = 'pending';

    INSERT INTO moderation_logs(property_id, admin_id, action) VALUES (:id, :admin_id, 'approve');

    INSERT INTO notifications(user_id, content)
    SELECT owner_id, CONCAT('Tin "', title, '" đã được duyệt.') FROM properties WHERE property_id = :id;
COMMIT;

-- 4.12 Admin: TỪ CHỐI tin (bắt buộc có lý do)
START TRANSACTION;
    UPDATE properties SET status = 'rejected'
    WHERE property_id = :id AND status = 'pending';

    INSERT INTO moderation_logs(property_id, admin_id, action, reason)
    VALUES (:id, :admin_id, 'reject', :reason);

    INSERT INTO notifications(user_id, content)
    SELECT owner_id, CONCAT('Tin "', title, '" bị từ chối: ', :reason) FROM properties WHERE property_id = :id;
COMMIT;

-- 4.13 Admin: khóa / mở khóa tài khoản
UPDATE users SET is_locked = NOT is_locked WHERE user_id = :user_id AND role = 'user';

-- 4.14 Tự động hết hạn (chạy hằng ngày bằng Laravel Scheduler hoặc MySQL EVENT)
UPDATE properties SET status = 'expired'
WHERE status = 'approved' AND expires_at <= NOW();

-- 4.15 Thông báo: số chưa đọc, danh sách, đánh dấu đã đọc
SELECT COUNT(*) FROM notifications WHERE user_id = :user_id AND is_read = 0;
SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 20;
UPDATE notifications SET is_read = TRUE WHERE notification_id = :id AND user_id = :user_id;
UPDATE notifications SET is_read = TRUE WHERE user_id = :user_id AND is_read = 0;

-- 4.16 Lịch hẹn: người đặt xem của mình; chủ tin xem lịch trên tin của mình
SELECT a.*, p.title FROM appointments a JOIN properties p ON p.property_id = a.property_id
WHERE a.user_id = :user_id ORDER BY a.scheduled_at;

SELECT a.*, p.title, up.full_name AS visitor
FROM appointments a
JOIN properties p ON p.property_id = a.property_id
JOIN user_profiles up ON up.user_id = a.user_id
WHERE p.owner_id = :user_id ORDER BY a.scheduled_at;

UPDATE appointments a JOIN properties p ON p.property_id = a.property_id
SET a.status = 'confirmed'
WHERE a.appointment_id = :id AND p.owner_id = :user_id;

-- 4.17 Báo cáo vi phạm: gửi, danh sách cho admin, xử lý
INSERT INTO reports(property_id, reporter_id, reason) VALUES (:id, :user_id, :reason);

SELECT r.report_id, r.reason, r.status, p.title, up.full_name AS reporter
FROM reports r
JOIN properties p ON p.property_id = r.property_id
JOIN user_profiles up ON up.user_id = r.reporter_id
WHERE r.status = 'open' ORDER BY r.created_at;

UPDATE reports SET status = 'resolved' WHERE report_id = :id;   -- hoặc 'dismissed'

-- 4.18 Dữ liệu cho bản đồ (tin đã duyệt có tọa độ)
SELECT property_id, title, price, latitude, longitude
FROM properties
WHERE status = 'approved' AND expires_at > NOW()
  AND latitude IS NOT NULL AND longitude IS NOT NULL;

-- =====================================================================
-- PHẦN 5: THỐNG KÊ CHO DASHBOARD ADMIN
-- =====================================================================

-- 5.1 Số tin theo trạng thái
SELECT status, COUNT(*) AS total FROM properties GROUP BY status ORDER BY total DESC;

-- 5.2 Số tin theo khu vực (tỉnh)
SELECT pr.name AS province, COUNT(*) AS total
FROM properties p
JOIN districts d ON d.district_id = p.district_id
JOIN provinces pr ON pr.province_id = d.province_id
GROUP BY pr.name ORDER BY total DESC;

-- 5.3 Số tin theo tháng (12 tháng gần nhất)
SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS total
FROM properties
WHERE created_at >= DATE_SUB(DATE_FORMAT(NOW(), '%Y-%m-01'), INTERVAL 11 MONTH)
GROUP BY month ORDER BY month;

-- 5.4 Người dùng mới theo tháng
SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS new_users
FROM users WHERE role = 'user'
GROUP BY month ORDER BY month;

-- 5.5 Top 5 tin được yêu thích nhiều nhất
SELECT p.property_id, p.title, COUNT(f.user_id) AS saves
FROM properties p JOIN favorites f ON f.property_id = p.property_id
GROUP BY p.property_id, p.title
ORDER BY saves DESC LIMIT 5;

-- 5.6 Tỷ lệ duyệt / từ chối
SELECT action, COUNT(*) AS total,
       ROUND(100 * COUNT(*) / SUM(COUNT(*)) OVER (), 1) AS percent
FROM moderation_logs GROUP BY action;

-- 5.7 Thẻ số liệu nhanh
SELECT
    (SELECT COUNT(*) FROM properties WHERE status = 'pending')                         AS pending,
    (SELECT COUNT(*) FROM properties WHERE status = 'approved' AND expires_at > NOW()) AS live,
    (SELECT COUNT(*) FROM users WHERE role = 'user'
        AND created_at >= DATE_FORMAT(NOW(), '%Y-%m-01'))                              AS new_users_month,
    (SELECT COUNT(*) FROM reports WHERE status = 'open')                               AS open_reports;