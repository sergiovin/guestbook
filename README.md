# Guestbook
Simple Symfony guestbook
Installation insructions
------------------------

  * Clone repository using git clone;
  
  * Install dependencies. Run: 
```shell
composer update
```
  * Edit app/config/parameters.yml to setup db parameters;

  * Create database tables by running: 
```shell
php bin/console doctrine:migrations:migrate  
```
  * Run test Web server:
```shell  
php bin/console server:start
```
  * Use Symfony console command to delete N last records from guestbook:
```shell  
php bin/console guestbook:delete N
```
  

