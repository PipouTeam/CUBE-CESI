Dans mon test UserTest la commande 

    phpunit ./tests/UserTest.php ne 
    
fonctionner pas quand j'appellais des fonction avec 

    use App\Models\User;

j'ai du installer via composer 

    composer require --dev phpunit/phpunit

et maintenant lancer les test de cette facon 

    ./vendor/bin/phpunit tests/UserTest.php
