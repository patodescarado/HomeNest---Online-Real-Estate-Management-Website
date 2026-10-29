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