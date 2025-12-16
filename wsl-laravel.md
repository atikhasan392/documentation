# Laravel 12 + MySQL (Ubuntu Full Setup Guide)

## System Update

```sh
sudo apt update && sudo apt upgrade -y
sudo apt install -y software-properties-common ca-certificates curl gnupg lsb-release unzip
```

---

## PHP 8.4 (Recommended for Laravel 12)

Ondřej Surý PPA is used because Ubuntu’s default repositories are always behind when it comes to PHP versions.

```sh
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
```

### PHP + Required Extensions (Optimized for Laravel 12)

```sh
sudo apt install -y \
php8.4 php8.4-cli php8.4-fpm \
php8.4-mysql php8.4-sqlite3 \
php8.4-xml php8.4-curl php8.4-mbstring \
php8.4-zip php8.4-bcmath php8.4-intl \
php8.4-gd php8.4-opcache php8.4-readline
```

### Verify PHP Installation

```sh
php -v
php -m
```

Expected output: **PHP 8.4.x CLI**

---

## Composer (Latest Stable)

Do **not** use the Composer version from Ubuntu repositories. It is outdated and unreliable for modern Laravel projects.

```sh
cd ~
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
composer -V
```

Expected output: **Composer 2.x (latest stable)**

---

## Node.js (Required for Laravel Vite)

NodeSource repository is used to ensure the latest **LTS** version.

```sh
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
sudo apt install -y nodejs
node -v
npm -v
```

Note: Laravel does **not** need bleeding-edge Node versions. LTS is the correct choice for stability.

---

## Bun (Fast Alternative to npm/yarn)

Bun provides faster installs, smaller `node_modules`, and works perfectly with Laravel + Vite.

```sh
curl -fsSL https://bun.sh/install | bash
source ~/.bashrc
bun -v
```

---

## MySQL 8 (Production-grade)

Ubuntu’s MySQL package is stable and fully compatible with Laravel.

```sh
sudo apt install -y mysql-server
sudo systemctl enable mysql
sudo systemctl start mysql
mysql --version
```

### Secure MySQL Installation

```sh
sudo mysql_secure_installation
```

---

## phpMyAdmin

Installed with Apache dependency. This is acceptable for **development environments only**.

```sh
sudo apt install -y phpmyadmin
```

During installation:

* Web server: **apache2**
* Configure database with dbconfig-common: **Yes**

Access via browser:

```sh
http://localhost/phpmyadmin
```

---

## Create Laravel 12 Project

Composer will automatically download the latest stable Laravel version.

```sh
composer create-project laravel/laravel project_name
cd project_name
```

### Environment Setup

```sh
cp .env.example .env
php artisan key:generate
```

### Database Configuration (`.env`)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

---

## Frontend Dependencies (Using Bun)

```sh
bun install
bun run dev
```

---

## Laravel Development Server

```sh
php artisan serve
```

Open in browser:

```sh
http://127.0.0.1:8000
```

---

## Performance Notes (Straight Talk)

* Running Laravel **without Docker** gives the fastest local development experience
* PHP-FPM is already installed, so switching to Nginx or Apache later is effortless
* Bun significantly reduces install time and disk usage
* Native MySQL provides lower I/O latency than containerized setups

---

## This Setup Is NOT For

* Developers stuck in a Windows-only mindset
* People who feel insecure without Docker
* Anyone who does not understand the difference between development and production environments
