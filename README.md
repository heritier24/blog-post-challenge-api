## These are the procedures to follow when setting up the app for the first time

-   Install composer dependencies
    
    composer install
    
-   Copy the .env.example to .env
    
    cp .env.example .env
    
-   Build the project *(keep this running in a tab in order to get access to the app)*
    
    php artisan serve
    
-   Generate the application key
    
    php artisan key:generate
    
-   Run migrations
    
    php artisan migrate:fresh

### run command to create default author

- run this command to setup default author to database

   php artisan setup:required-data

### artisan command to load autoload 

   composer dump-autoload