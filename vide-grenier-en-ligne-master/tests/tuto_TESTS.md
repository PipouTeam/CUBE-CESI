# Step 1 - Installation de PhpUnit

```bash
sudo apt install phpunit
```

Vérification de l'installation :
```bash
phpunit --version
```

# Step 2 - Création d'un test
Pour plus de lisibilité, un dossier `tests` a été créé.  
Avec l'utilisation de PhpUnit, les tests sont des méthodes dans des classes qui ont hérité de `PHPUnit\Framework\TestCase`. Chaque méthode de test doit commencer par "test" pour être reconnue par PHPUnit.  


```php
<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class GreeterTest extends TestCase
{
    // La fonction commence par "test" pour être reconnue par PHPUnit
    public function testGreetsWithName(): void
    {
        $greeter = new Greeter;

        $greeting = $greeter->greet('Alice');

        $this->assertSame('Hello, Alice!', $greeting);
    }

    public function testGreetsWithNoName(): void
    {
        $greeter = new Greeter;

        $greeting = $greeter->greet('');

        $this->assertSame('Hello !', $greeting);
    }
}

final class Greeter
{
    public function greet(string $name): string
    {
        if (empty($name)) {
            return 'Hello !';
        }

        return 'Hello, ' . $name . '!';
    }
}
```

# Step 3 - Exécution des tests

```bash
phpunit ./tests/GreeterTest.php
```

Exécution des tests avec succès :
```bash
PHPUnit 9.6.20 by Sebastian Bergmann and contributors.

.                                                                   1 / 1 (100%)

Time: 00:00.003, Memory: 4.00 MB

OK (1 test, 1 assertion)
```
