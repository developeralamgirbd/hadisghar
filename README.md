# Laravel Blog CMS

## After Clone Application for share hosting


- command: cp .env.example .env
- APP_NAME=AppName
- APP_ENV=production
- APP_DEBUG=false
- APP_URL=your domain name with http/https
### Database setup
- DB_DATABASE=database_name
- DB_USERNAME=database_username
- DB_PASSWORD=database_password
- 
- CACHE_DRIVER=database
### Run command
- composer install --optimize-autoloader --no-dev
- php artisan key:generate
- php artisan migrate:fresh --seed

### Create (uploads, images) folder in public directory 
### Create (feature) folder in public/images directory 





