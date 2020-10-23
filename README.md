# cd to the location where you cloned the project
cd /var/www/html/symfony

# start the containers
docker-compose up -d 


# Open the container
docker-compose exec symfony4-php-fpm bash

# inside symfony4-php-fpm bash, create project symfony
composer create-project symfony/skeleton symfony ^4.4.0

# inside symfony4-php-fpm bash
mv /application/symfony/* /application


# inside symfony4-php-fpm bash
rm -Rf /application/symfony


# inside symfony4-php-fpm bash
cd /application

composer require annotations
composer require --dev profiler
composer require twig
composer require orm
composer require form
composer require form validator
composer require maker-bundle


# Sync de database
.env file that has been generated when requiring the orm package in Symfony4.

DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

So if you check the docker-compose.yml file, you’ll see the credentials under the mysql configuration. So change the file to

DATABASE_URL=mysql://root:root@127.0.0.1:3306/social-posts?serverVersion=5.7


# restart the containers
cd /var/www/html/symfony
docker-compose down
docker-compose up -d
docker-compose exec symfony4-php-fpm bash


# Connect to  database with MysqlWorkbench
Host: 127.0.0.1
Username: root
Password: root
port: 8002


# Open aplication
http://localhost:8000


# inside php-fpm bash, make a controller
cd /application

bin/console make:controller SocialPosts