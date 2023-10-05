<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Cách chạy đồ án:
1. Clone đồ án về máy
2. Chạy lệnh composer install và npm install 
3. Copy file .env.example và đổi tên thành .env .Sau đó thay đổi các dòng DB_DATABASE, DB_USERNAME, DB_PASSWORD theo cấu hình của máy
4. Chạy lệnh php artisan migrate. Nếu lệnh bị lỗi chạy php artisan migrate:fresh để drop all dữ liệu đã lưu vào db
5. Chạy lệnh php artisan db:seed
6. Chạy php artisan serve để thực hiện chạy server.

mssv: 312
pass: 312

Ngoài ra các tài khoản khác sẽ có pass trùng với mssv, xem thêm trong db
