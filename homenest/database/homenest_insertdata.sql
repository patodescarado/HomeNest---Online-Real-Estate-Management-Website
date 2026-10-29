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
