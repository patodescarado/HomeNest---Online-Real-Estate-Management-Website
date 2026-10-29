**HomeNest: Hướng dẫn cài đặt và chạy dự án**



**1. Chuẩn bị môi trường**

Công cụ	Ghi chú

PHP	Dùng phiên bản mà composer.json yêu cầu (mục require → php). Laravel 13 cần PHP 8.5

Composer	Tải tại getcomposer.org

MySQL	Có thể dùng MySQL của XAMPP

Git	Để tải dự án (hoặc tải file ZIP từ GitHub)



Bật extension PHP. Mở file php.ini của PHP đang dùng (kiểm tra bằng php --ini, xem dòng Loaded Configuration File), xóa dấu ; ở đầu các dòng sau:



extension=curl

extension=fileinfo

extension=mbstring

extension=openssl

extension=pdo\_mysql

extension=mysqli

extension=zip



Nếu extension\_dir đang bị comment, đặt đúng đường dẫn tới thư mục ext của PHP. Lưu file rồi mở lại terminal, kiểm tra bằng:



powershell

php -m



**2. Tải dự án**

powershell

git clone https://github.com/patodescarado/HomeNest---Online-Real-Estate-Management-Website.git HomeNest

cd HomeNest

(Hoặc tải ZIP từ GitHub, giải nén, rồi mở terminal tại thư mục đó.)



3\. Cài thư viện

powershell

composer install



Thư mục vendor/ không được đưa lên GitHub vì rất nặng, nên lệnh này tải lại toàn bộ thư viện theo composer.json.



4\. Tạo file cấu hình .env

powershell

copy .env.example .env

php artisan key:generate



File .env chứa mật khẩu và cấu hình riêng từng máy nên không có trên GitHub. key:generate tạo khóa mã hóa cho ứng dụng, bắt buộc phải có.



5\. Tạo database

Bật MySQL trong XAMPP Control Panel.

Vào http://localhost/phpmyadmin, tạo database trống tên homenest\_database (collation utf8mb4\_unicode\_ci).

Mở .env, sửa phần database cho khớp với máy bạn:

DB\_CONNECTION=mysql

DB\_HOST=127.0.0.1

DB\_PORT=3306

DB\_DATABASE=homenest\_database

DB\_USERNAME=root

DB\_PASSWORD=



Cũng trong .env, đặt APP\_URL=http://localhost:8000.



6\. Tạo bảng và dữ liệu mẫu

powershell

php artisan migrate --seed



Lệnh này tạo toàn bộ bảng và nạp dữ liệu cố định (tỉnh/thành, quận/huyện, loại hình BĐS, tài khoản admin mẫu).



7\. Liên kết thư mục ảnh

powershell

php artisan storage:link



Bắt buộc để ảnh tin đăng hiển thị được.



