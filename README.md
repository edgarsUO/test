Welcome to Symfony test app.

Install instructions:
1. Clone repository                                             `git clone git@github.com:edgarsUO/test.git`
2. Free up `localhost` ports                                    `80, 5432, 9000`
3. From `<project_root>/docker` execute                         `docker-compose up --build`
4. Navigate to api doc                                          `http://localhost/api/doc`
5. Navigate to php container                                    `docker exec -it -u www-data php8.2-fpm bash`
6. Load data fixtures                                           `bin/console doctrine:fixtures:load`
7. Update currency rates                                        `bin/console app:update-rates`
8. Execute tests                                                `vendor/bin/phpunit --coverage-text`
9. Use included file `src/requests.http` for request examples (use uuid's from database fixtures)

Troubleshooting:
1. System must be able to support Postgres 16. In case `dpkg -l | grep postgresql` does not
   contain `postgresql-client-16`, please install it by running command `sudo apt install postgresql-16`
2. In case there are file permissions issues related to cache update `<project_root>/docker/php-fpm/Dockerfile`
   at line 18 `RUN usermod -u <your_docker_user> www-data` and rebuild docker container `docker-compose up --build`

Notes:
1. Due to insufficient time, tests cover only rates api functionality. App functionality tests are still to be added.
2. Database locking should be implemented for transactions to avoid partial execution.
3. Validators and serializers should be covered by tests.
4. For testing simplicity, `<project_root>/docker/.env` already contains all variables, though a bad practice.
5. PHP-FPM dockerfile should be refactored to use alpine distro for improved performance.
