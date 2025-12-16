# Laravel 12 + PHP 8.4 + MySQL 8 + phpMyAdmin Setup Guide (Ubuntu / WSL)

## 1. System Update & Required Tools

Update Ubuntu and install essential tools.

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y software-properties-common ca-certificates curl gnupg lsb-release unzip
```

---

## 2. PHP 8.4 Installation

Use Ondřej Surý PPA for latest PHP 8.4 compatible with Laravel 12.

```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y \
php8.4 php8.4-cli php8.4-fpm \
php8.4-mysql php8.4-sqlite3 \
php8.4-xml php8.4-curl php8.4-mbstring \
php8.4-zip php8.4-bcmath php8.4-intl \
php8.4-gd php8.4-opcache php8.4-readline
sudo update-alternatives --set php /usr/bin/php8.4
php -v
```

---

## 3. Composer Installation

Install latest Composer system-wide.

```bash
cd ~
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
composer -V
```

---

## 4. Node.js (LTS)

Install Node LTS for Laravel Vite.

```bash
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
sudo apt install -y nodejs

# Updating npm
sudo npm install -g npm@latest
node -v
npm -v
```

---

## 5. Bun Installation

Install Bun for fast frontend dependency management.

```bash
curl -fsSL https://bun.sh/install | bash
source ~/.bashrc
bun -v
```

---

## 6. MySQL 8 Installation

Install MySQL server and enable it.

```bash
sudo apt install -y mysql-server
sudo systemctl enable mysql
sudo systemctl start mysql
mysql --version
```

Set root password compatible with phpMyAdmin:

```bash
sudo mysql
```

```sql
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';
FLUSH PRIVILEGES;
EXIT;
```

---

## 7. phpMyAdmin Installation (Manual, PHP 8.4 Compatible)

Download and extract phpMyAdmin manually to avoid PHP downgrade.

```bash
cd /var/www
sudo wget https://files.phpmyadmin.net/phpMyAdmin/5.2.3/phpMyAdmin-5.2.3-all-languages.tar.gz
sudo tar xzf phpMyAdmin-5.2.3-all-languages.tar.gz
sudo mv phpMyAdmin-5.2.3-all-languages phpmyadmin
sudo chown -R www-data:www-data phpmyadmin
```

Run built-in PHP server for phpMyAdmin:

```bash
cd /var/www/phpmyadmin
php -S 127.0.0.1:8080
```

Access via browser: `http://127.0.0.1:8080`

Configure database connection:

```bash
sudo cp config.sample.inc.php config.inc.php
sudo nano config.inc.php
```

Set inside `config.inc.php`:

```php
$cfg['Servers'][$i]['auth_type'] = 'cookie';
$cfg['Servers'][$i]['host'] = '127.0.0.1';
$cfg['Servers'][$i]['user'] = 'root';
$cfg['Servers'][$i]['password'] = 'root';
```

---

## 8. Create Laravel 12 Project

```bash
composer create-project laravel/laravel project_name
cd project_name
cp .env.example .env
php artisan key:generate
```

Configure `.env` for MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
```

---

## 9. Frontend Dependencies

```bash
bun install
bun run dev
```

---

## 10. Laravel Development Server

```bash
php artisan serve
```

Access via browser: `http://127.0.0.1:8000`

---

## Notes

* Built-in PHP server used for both Laravel and phpMyAdmin, avoids PHP version conflicts.
* Bun reduces `node_modules` size and speeds up Vite builds.
* Docker is **not required** for fast local development.

