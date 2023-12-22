##Install Dependencies
```
composer install
```


##Database Setup
```
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

##Load Data Fixtures
```
php bin/console doctrine:fixtures:load
```

##Running Tests
```
php bin/phpunit
```
