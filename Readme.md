﻿﻿Symfony-docker-blog-api
=======================

## How to run

- Go to `/app` directory
- Run `composer install`
- Create database `php bin/console doctrine:migrations:migrate`
- Before running tests, create test db

```
php bin/console --env=test doctrine:database:create 
php bin/console --env=test doctrine:schema:create 
```

- Run `docker-compose up -d --build` in main directory
- To ensure app works properly, run tests `php ./vendor/bin/phpunit`
- Open `http://localhost:8080/` and enjoy

## Documentation

- Open `http://localhost:8080/api` - you will see Swagger UI for blog endpoints