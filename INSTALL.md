## Installing an Employee API app
### Docker
1. Clone the application
     ```
    git clone git@github.com:kacperjurak/employee-api.git
    ```

1. Open a terminal in the directory where you downloaded an application, and build the docker environment
    ```
    docker-compose build --pull
    ```

3. Run the docker environment
    ```
    docker-compose up
    ```
   You can also run application in daemon mode,
    ```
    docker-compose up -d
    ```
   and see the container's logs using
    ```
    docker-compose logs -f
    ```

### Access to the application
When running app, you can access to:

#### RET API
[https://localhost](https://localhost) (employees andpoint - [https://localhost/employees](https://localhost/employees))

#### API DOCS
Docs are generated automatically
You can run requests directly from Swagger UI (don't need an external REST client)
[https://localhost/docs](https://localhost/docs) Swagger
[https://localhost/docs?ui=re_doc](https://localhost/docs?ui=re_doc) ReDoc

#### Adminer
Adminer is a simple PHP database manager (like phpMyAdmin)
Select PostgreSQL database type. Database name and credentials can be found in `api/.env` file
Default dev database name: `api`
Default dev database user: `api-platform`
[https://localhost:8001](https://localhost:8001)

#### Shell
To access Linux shell in a PHP container run:
```
docker-compose exec php sh
```

#### Testing
To run tests:
1. Access the PHP shell
    ```
    docker-compose exec php sh
    ```
2. Create test database
    ```
    bin/console doctrine:database:create --env=test
    ```
3. Execute a migrations
    ```
    bin/console doctrine:migrations:migrate --env=test
    ```
4. run tests
    ```
    bin/phpunit
    ```  

#### Messenger
To handle Messenger messages (needed for getting ID from reqres.io):
1. Access the PHP shell
    ```
    docker-compose exec php sh
    ```
2. Run consume command
    ```
    bin/console messenger:consume async -vv
    ```
