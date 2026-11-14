# HomeNest

HomeNest là website quản lý và đăng tin bất động sản. Người dùng có thể tìm tin mua bán/cho thuê, đăng tin, lưu tin yêu thích và quản lý tin của mình. Quản trị viên có thể duyệt tin đăng.

## Chạy website trên máy cá nhân

### Yêu cầu

- PHP theo yêu cầu trong `composer.json` (hiện tại PHP `^8.3`).
- Composer.
- Node.js và npm.
- MySQL, ví dụ MySQL đi kèm XAMPP.
- Git để tải mã nguồn.

### 1. Tải mã nguồn và cài thư viện

Mở PowerShell tại thư mục muốn lưu dự án:

```powershell
git clone https://github.com/patodescarado/HomeNest---Online-Real-Estate-Management-Website.git
cd HomeNest---Online-Real-Estate-Management-Website\Homenest
composer install
npm ci
```

Nếu tải mã nguồn dưới dạng ZIP, hãy giải nén rồi mở PowerShell tại thư mục `Homenest` (thư mục có file `artisan`).

### 2. Cấu hình ứng dụng

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Mở file `.env` và cấu hình kết nối MySQL. Ví dụ với XAMPP mặc định:

```dotenv
APP_NAME=HomeNest
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homenest_database
DB_USERNAME=root
DB_PASSWORD=
```

Trong XAMPP Control Panel, bật MySQL rồi tạo database `homenest_database` bằng phpMyAdmin. Nếu MySQL trên máy bạn có mật khẩu hoặc dùng cổng khác, cập nhật các giá trị tương ứng trong `.env`.

### 3. Tạo bảng, dữ liệu mẫu và liên kết ảnh

```powershell
php artisan migrate --seed
php artisan storage:link
```

Seeder tạo dữ liệu mẫu như địa điểm, loại bất động sản, tin đăng và tài khoản dùng thử.

### 4. Biên dịch giao diện và khởi động

```powershell
npm run build
php artisan serve
```

Mở website tại **http://127.0.0.1:8000**. Giữ cửa sổ PowerShell đang chạy `php artisan serve` trong khi sử dụng website.

## Hướng dẫn sử dụng

- **Khách truy cập:** xem tin, tìm kiếm và lọc tin mua bán/cho thuê.
- **Tài khoản người dùng:** đăng ký hoặc đăng nhập để lưu tin yêu thích, đăng tin và quản lý các tin của mình.
- **Quản trị viên:** đăng nhập bằng tài khoản quản trị để vào `/admin`, xem và duyệt tin đăng.

### Tài khoản dùng thử local

Sau khi chạy `php artisan migrate --seed`, có thể đăng nhập bằng:

| Vai trò | Email | Mật khẩu |
| --- | --- | --- |
| Quản trị viên | `admin@homenest.vn` | `password` |
| Người dùng | `user1@example.com` | `password` |

Đây là thông tin mẫu dành riêng cho máy phát triển local. **Không sử dụng các tài khoản/mật khẩu này trên website công khai.**

## Một số lỗi thường gặp

- **Không kết nối được MySQL:** kiểm tra MySQL đã bật trong XAMPP chưa, database đã được tạo chưa và thông tin `DB_*` trong `.env` đã đúng chưa.
- **Thiếu khóa ứng dụng:** chạy `php artisan key:generate`.
- **Ảnh tin đăng không hiển thị:** chạy `php artisan storage:link`.
- **Giao diện hoặc tài nguyên bị thiếu:** chạy lại `npm ci` và `npm run build`.

## Công nghệ

- Laravel 13, PHP.
- MySQL.
- Blade, Tailwind CSS và Vite.
