# E-Boi Project

### Basic implementation of an e-commerce project

## Setup:

### 1. Clone repo:
```sh
git clone git@github.com:peekle86/e-boi.git
```
### 2. Go to the project folder and install composer dependencies:
```shell
composer install
```
### 3. Copy .env.example file to .env
```shell
cp .env.example .env
```
### 4. Configure DB setting in .env file (example for mysql):
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=comentor
DB_USERNAME=root
DB_PASSWORD=
```
### 5. Run migrations with seeders:
```shell
php artisan migrate --seed
```
### 6. Generate application key
```shell
php artisan key:generate
```
### 7. Install npm dependencies
```shell
npm install
```
### 8. Build npm dependencies
```shell
npm run build
```

### Admin panel available by /admin path with credentials:
```
Login: admin@mail.com
Password: password
```
