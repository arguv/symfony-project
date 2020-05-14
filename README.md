Symfony 3.4 Standard Edition
========================

What's use?
--------------

* PHP > 5.5.9
* Doctrine ^2.5
* Twig ^1.0||^2.0
* Composer
* PostgreSQL

**composer install**  
**composer dump-autoload**  
**php bin/console doctrine:schema:update --force**  

**php bin/console doctrine:cache:clear-metadata**  
**php bin/console doctrine:cache:clear-result**  
**php bin/console doctrine:cache:clear-query**  
**php bin/console cache:clear**  
**chmod -R 777 var/**