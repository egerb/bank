## Requirements

* php 7.0 with extensions for Laravel 5.5
* mySQL
* composer
* node
* npm

## Setup
* Clone repo:
``git clone git@github.com:egerb/test.git .``

* Install php dependecies:
``php composer install``
* Install node modules
``npm install``
* Compile assets
``npm run dev``

## Set up .env using .env.example

* Configure DB connection
````
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=test
DB_USERNAME=root
DB_PASSWORD=root
````

* Run migrations
``php artisan migrate``

* Run seeds for dummy data:
``php artisan db:seed``


* Run http://App_URL

* Register new user

* Run ``php artisan passport:keys`` to deploy Passport

* Use your credentials to log in and see dummy data.

* Run ``php artisan route:list`` to get all available rotes.

To test rest of APIs - go to Manage oauth link on dashboard, to get access for managing clients and tokens. 

# Cron

* Add to cron configuration next string, to save data about sum of transactions for every previous day, every 2 days at 23:47:

``
0      23     */2       *       * path_to_laravel_root_dir/php artisan save:total 
``

You can pass optional parameter php artisan save:total 2.05.2018 to save sum for exact date
