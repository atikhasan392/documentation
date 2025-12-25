# Ubuntu-24.4 LTS / WSL 2

## 1. System Update & Required Tools

Update Ubuntu and install essential tools.

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y software-properties-common ca-certificates curl gnupg lsb-release unzip
```

## 2. PHP 8.4 Installation

```bash
sudo apt update
sudo apt install -y software-properties-common
sudo LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.4
```

Install PHP extensions

```bash
sudo apt install -y php8.4-bcmath php8.4-curl php8.4-dom php8.4-gd php8.4-mbstring php8.4-mysql php8.4-pdo-mysql php8.4-pdo-sqlite php8.4-xml php8.4-zip php8.4-mysqli
```

To check all extensions

```bash
php8.4 -m | grep -E "bcmath|ctype|curl|dom|fileinfo|gd|filter|json|mbstring|openssl|pcre|pdo|pdo_mysql|pdo_sqlite|session|tokenizer|xml|zip|mysqli"
```

## 3. Composer Installation

Install latest Composer system-wide.

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'c8b085408188070d5f52bcfe4ecfbee5f727afa458b2573b8eaaf77b3419b0bf2768dc67c86944da1544f06fa544fd47') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

```bash
sudo mv composer.phar /usr/local/bin/composer
```

## 4. Node.js & NPM Installation

Install Node Current 25.2.1 for Laravel Vite.

```bash
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.3/install.sh | bash
\. "$HOME/.nvm/nvm.sh"
nvm install 25

sudo apt update
sudo apt install -y libatomic1

node -v
npm -v
```

## 5. Bun Installation

Install Bun for fast frontend dependency management.

```bash
sudo curl -fsSL https://bun.sh/install | bash
source ~/.bashrc
bun -v
```

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
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';
ALTER USER 'root'@'localhost' IDENTIFIED BY '';
FLUSH PRIVILEGES;
EXIT;
```

### 7. Apache 2 Installation

```bash
sudo apt update
sudo apt install apache2
```

## 8. phpMyAdmin Installation (Manual, PHP 8.4 Compatible)

```bash
cd /var/www/html

sudo wget https://files.phpmyadmin.net/phpMyAdmin/5.2.3/phpMyAdmin-5.2.3-all-languages.tar.gz
sudo tar xzf phpMyAdmin-5.2.3-all-languages.tar.gz
sudo mv phpMyAdmin-5.2.3-all-languages phpmyadmin
sudo rm phpMyAdmin-5.2.3-all-languages.tar.gz
sudo mkdir -p /var/www/html/phpmyadmin/tmp
sudo chown -R www-data:www-data /var/www/html/phpmyadmin
sudo chmod 777 /var/www/html/phpmyadmin/tmp
cd phpmyadmin
```

Configure database connection:

```bash
sudo cp config.sample.inc.php config.inc.php
sudo nano config.inc.php
```

Set inside `config.inc.php`:

```php
<?php
$cfg['blowfish_secret'] = 'JOFw435365IScA8Q1cDugr4lSfuAzqun';

$i = 0;
$i++;
$cfg['Servers'][$i]['auth_type'] = 'config';
$cfg['Servers'][$i]['host'] = '127.0.0.1';
$cfg['Servers'][$i]['user'] = 'root';
$cfg['Servers'][$i]['password'] = '';
$cfg['Servers'][$i]['AllowNoPassword'] = true;

$cfg['TempDir'] = '/var/www/html/phpmyadmin/tmp';
$cfg['UploadDir'] = '';
$cfg['SaveDir'] = '';
```

```bash
cd /var/www/html/phpmyadmin
php -S 127.0.0.1:8080
```

Access via browser: `http://127.0.0.1:8080`

## 9. Git Update (Latest Version)

```bash
sudo add-apt-repository -y ppa:git-core/ppa
sudo apt update; apt install -y git
git --version
```

```bash
git config --global user.name "ATik HaSan"
git config --global user.email "atikhasan2700@gmail.com"
```

## 10. Install GitHub CLI

```bash
sudo apt install gh
gh auth login
```

## 10. Check All Version

```bash
php -v # 8.4.16
composer -V # 2.9.2
node -v # v25.2.1
npm -v # 11.6.2
bun -v # 1.3.5
mysql --version # 8.0.44
```





