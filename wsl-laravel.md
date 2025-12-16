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
sudo rm composer-setup.php
composer -V
```

---

## 4. Node.js (Current 25.2.1)

Install Node Current 25.2.1 for Laravel Vite.

```bash
cd /tmp
curl -O https://nodejs.org/dist/v25.2.1/node-v25.2.1-linux-x64.tar.xz
tar -xf node-v25.2.1-linux-x64.tar.xz
sudo cp -r node-v25.2.1-linux-x64/* /usr/local/

sudo apt update
sudo apt install -y libatomic1

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

# Create TempDir and fix permissions
sudo mkdir -p /var/www/html/phpmyadmin/tmp
sudo chown -R www-data:www-data /var/www/html/phpmyadmin/tmp
sudo chmod 777 /var/www/html/phpmyadmin/tmp

sudo rm phpMyAdmin-5.2.3-all-languages.tar.gz
```

Run built-in PHP server for phpMyAdmin:

```bash
cd /var/www/html/phpmyadmin
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
$cfg['blowfish_secret'] = 'JOFw435365IScA&Q!cDugr!lSfuAz*OW'; // keep any long random string
$i = 0;
$i++;
$cfg['Servers'][$i]['auth_type'] = 'config';      // changed from 'cookie' to 'config'
$cfg['Servers'][$i]['host'] = '127.0.0.1';
$cfg['Servers'][$i]['user'] = 'root';            // your MySQL username
$cfg['Servers'][$i]['password'] = 'root';        // your MySQL password

$cfg['TempDir'] = '/var/www/html/phpmyadmin/tmp';
$cfg['UploadDir'] = '';
$cfg['SaveDir'] = '';
```

Edit systemd service file:

```bash
sudo nano /etc/systemd/system/phpmyadmin-dev.service
```

Write this inside:

```ini
[Unit]
Description=PHP Built-in Server for phpMyAdmin
After=network.target

[Service]
User=ore
WorkingDirectory=/var/www/html/phpmyadmin
ExecStart=/usr/bin/php -S 127.0.0.1:8080
Restart=always

[Install]
WantedBy=multi-user.target
```

Systemd reload + restart:

```bash
sudo systemctl daemon-reload
sudo systemctl enable phpmyadmin-dev
sudo systemctl restart phpmyadmin-dev
sudo systemctl status phpmyadmin-dev
```

---

## 8. Git Update (Latest Version)

```bash
sudo add-apt-repository -y ppa:git-core/ppa
sudo apt update
sudo apt install -y git
git --version
```

```bash
git config --global user.name "ATik HaSan"
git config --global user.email "atikhasan2700@gmail.com"
```

