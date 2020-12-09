# XML Importer Application

### Starting Application
- Use ```docker-compose up -d``` to start Docker Containers

- First time you need to enter API container (```docker-compose exec api sh```) and execute ```php artisan migrate ``` to migrate Mysql Database
- Still inside the container run ```php artisan passport:install``` to start Passport Authentication

### Documentation
- After start application, please access: http://localhost/api/documentation

### Postman Collection
- Link to import the API Collection: https://www.getpostman.com/collections/850e7a2cc3874e9f805e

### XML Upload Page
- http://localhost
