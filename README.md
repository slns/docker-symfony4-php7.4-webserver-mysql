# Docker, Mysql 8, PHP 7.4, NGINX, Symfony 4, Faker

## Documentation API
http://localhost:8000/api/doc


## Docker
Open in root project ( ex: cd My/project)

### cd to the location where you cloned the project
cd /var/www/html/symfony

### start the containers
```
docker-compose up -d 
```

### Open the container
```
docker-compose exec symfony4-php-fpm bash
```

### Install Dependencies
```
composer install
```


# Connect to  database with MysqlWorkbench
```
Host: 127.0.0.1
Username: root
Password: root
port: 8002
```

# Open aplication in Postman
> GET ALL - http://localhost:8000/api/posts/

> GET ONE - http://localhost:8000/api/posts/1

> POST - http://localhost:8000/api/posts/

> PUT or PATCH - http://localhost:8000/api/posts/1

> DELETE - http://localhost:8000/api/posts/1
