## Project Setup (Laravel JWT Auth)

Clone the project.

```
git clone https://github.com/nahidnfr123/FingerprintTechnologyTest.git
````

### Frontend:

```
cd task_frontend && cp .env.example .env
```

```
npm install && npm run dev
```

---------------------------------------------

### Backend:

Copy the .env.example file and rename to .env
```
cd task_api && cp .env.example .env
```

```php
composer install
```

Generate Key

```php
php artisan key:generate
```

Generate Passport Secret

```php
php artisan passport:install
```
### Create a mysql database and add database credentials to .env file
<br/>  
Run Migration

```php
php artisan migrate:fresh
```

### Setup mailtrap account or any other mail service in .env

Start Server

```php
php artisan ser
```

Start Queue

```php
php artisan queue:work
```
