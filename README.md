# laravel_qr_attendance

```
laravel new laravel_qr_attendance
```

```
php artisan make:migration add_mobile_no_to_users_table
```

```
php artisan make:model Event -m
```

```
php artisan make:model Attendance -m
```

```
php artisan make:filament-resource Event --generate
```

```
php artisan make:filament-resource User --generate
```

```
php artisan make:filament-resource Attendance --generate
```

```
https://packagist.org/packages/simplesoftwareio/simple-qrcode
```

```
php artisan make:controller QRController
```

```
composer require barryvdh/laravel-dompdf
```

```
php artisan make:controller ReportController
```

```
php artisan make:controller api/UserController
```

```
php artisan make:controller api/AuthController
```

```
php artisan make:controller api/AttendanceController
```