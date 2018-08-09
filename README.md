# Squad API
A social network system for I2IT - API
## Set up
- Clone the project
- Goto project dir
- Run `composer install`
- Copy file *.env.example* as *.env*
- Set up *Database Credentials* and *Application Url*
- Run `php artisan key:generate`
- Run `php artisan migrate`, add parameter `--seed` for fake testing data
- Run `php artisan serve` to serve for testing
