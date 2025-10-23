
# 🧩 Local Nginx Development Environment (Windows 11)

This guide sets up a **complete local development environment** using **Nginx, PHP, MySQL, and phpMyAdmin**. It works as a lightweight **XAMPP alternative** and supports Laravel & WordPress projects.

---

## 📦 Requirements

| Software | Link |
|----------|------|
| Nginx (Stable Windows) | https://nginx.org/en/download.html |
| PHP (Thread Safe ZIP) | https://windows.php.net/download/ |
| MySQL Community Server | https://dev.mysql.com/downloads/mysql/ |
| phpMyAdmin | https://www.phpmyadmin.net/downloads/ |
| DarkOrange Theme (Optional) | https://github.com/atikhasan392/DarkOrange |

---

## 📁 Folder Structure

```
C:\
 └── nginx\
      ├── nginx\
      │    ├── conf\
      │    │    └── nginx.conf
      │    └── nginx.exe
      ├── php\
      │    └── php.ini
      ├── phpMyAdmin\
      │    ├── themes\
      │    │    └── DarkOrange\
      │    └── config.inc.php
      └── www\
           ├── index.php
           └── phpinfo.php
```

- index.php

```php
<?php echo "<h1>Welcome to Local Nginx Server</h1>"; ?>
```

- phpinfo.php

```php
<?php phpinfo(); ?>
```

---

## ⚙️ PHP Configuration

- Copy `php.ini-development` → `php.ini`
- Enable extensions for Laravel, WordPress, phpMyAdmin:

```ini
extension=curl
extension=ftp
extension=fileinfo
extension=gd
extension=intl
extension=mbstring
extension=exif
extension=mysqli
extension=openssl
extension=pdo_mysql
extension=zip
extension=sockets
```

- Set timezone:

```ini
date.timezone = "Asia/Dhaka"
```

- Maximum allowed files size

```php
upload_max_filesize = 512M
post_max_size = 512M
max_execution_time = 600
memory_limit = 1024M
max_input_time = 600
max_input_vars = 5000
file_uploads = On
upload_tmp_dir = "C:\nginx\php\tmp"
display_errors = On
error_reporting = E_ALL
```

- MySQL Configuration (my.ini)

```sql
max_allowed_packet = 512M
innodb_log_file_size = 256M
wait_timeout = 600
interactive_timeout = 600
```

- Add PHP to PATH: `C:\nginx\php`

---

## ⚙️ Nginx Configuration (`nginx.conf`)

```nginx
worker_processes 1;

events { worker_connections 1024; }

http {
    include mime.types;
    default_type application/octet-stream;
    sendfile on;
    keepalive_timeout 65;

    server {
        listen 80;
        server_name localhost;
        root C:/nginx/www;
        index index.php index.html;

        # Allow large file uploads
        client_max_body_size 512M;

        location / {
            try_files $uri $uri/ =404;
        }

        location ~ \.php$ {
            include fastcgi_params;
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

            # Increase buffer sizes for big uploads
            fastcgi_buffers 16 16k;
            fastcgi_buffer_size 32k;
        }

        location /phpmyadmin {
            alias C:/nginx/phpMyAdmin;
            index index.php index.html;
            location ~ \.php$ {
                include fastcgi_params;
                fastcgi_pass 127.0.0.1:9000;
                fastcgi_index index.php;
                fastcgi_param SCRIPT_FILENAME $request_filename;
            }
        }
    }
}
```

---

## ⚙️ phpMyAdmin Configuration

- Copy `config.sample.inc.php` → rename `config.inc.php`
- Set blowfish secret:

```php
$cfg['blowfish_secret'] = '0c370a8b5997d50ea9d85494a13bba11';
```

- Server authentication:

```php
$i = 0;
$i++;
$cfg['Servers'][$i]['auth_type'] = 'cookie';
$cfg['Servers'][$i]['host'] = 'localhost';
$cfg['Servers'][$i]['compress'] = false;
$cfg['Servers'][$i]['AllowNoPassword'] = false;
```

- Optional: enable storage tables for advanced features:

```php
$cfg['Servers'][$i]['pmadb'] = 'phpmyadmin';
$cfg['Servers'][$i]['bookmarktable'] = 'pma__bookmark';
$cfg['Servers'][$i]['relation'] = 'pma__relation';
$cfg['Servers'][$i]['table_info'] = 'pma__table_info';
$cfg['Servers'][$i]['table_coords'] = 'pma__table_coords';
$cfg['Servers'][$i]['pdf_pages'] = 'pma__pdf_pages';
$cfg['Servers'][$i]['column_info'] = 'pma__column_info';
$cfg['Servers'][$i]['history'] = 'pma__history';
```

- Optional: set upload/save directories:

```php
$cfg['UploadDir'] = '';
$cfg['SaveDir'] = '';
```

- Optional: import storage tables in MySQL:

```sql
CREATE DATABASE phpmyadmin;
SOURCE C:/nginx/phpMyAdmin/sql/create_tables.sql;
```

---

## ⚙️ Start Servers

```cmd
C:\nginx\nginx> start nginx
C:\nginx\php> php-cgi.exe -b 127.0.0.1:9000
```

- Reload Nginx:

```cmd
C:\nginx\nginx> nginx -s reload
```

- Stop Nginx:

```cmd
C:\nginx\nginx> nginx -s stop
```

---

## ⚙️ Test URLs

- [http://localhost/](http://localhost/) → index.php
- [http://localhost/phpinfo.php](http://localhost/phpinfo.php) → PHP info
- [http://localhost/phpmyadmin](http://localhost/phpmyadmin) → phpMyAdmin

---

## ✅ Done!

This setup is a **professional lightweight XAMPP alternative** ready for **PHP, Laravel, WordPress, phpMyAdmin development** with optional DarkOrange theme for phpMyAdmin.
