## Ubuntu update

```sh
sudo apt update && sudo apt upgrade -y
```

## PHP 8.4 + Laravel required extension

```sh
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.4 php8.4-cli php8.4-fpm php8.4-mysql \
php8.4-sqlite3 php8.4-xml php8.4-curl php8.4-mbstring php8.4-zip \
php8.4-bcmath php8.4-intl php8.4-gd
php -v
php -m
```

## Composer (PHP package manager)

```sh
cd ~
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
composer -V
```

## Node.js (Laravel Vite / JS bundling)

```sh
sudo apt update
sudo apt install unzip -y

curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
node -v
npm -v
```

## Bun (fast replacement for npm/yarn)

```sh
curl -fsSL https://bun.sh/install | bash
source ~/.bashrc
bun -v
```

## Laravel project test

```sh
composer create-project laravel/laravel test-app
cd test-app
bun install
php artisan serve

```
