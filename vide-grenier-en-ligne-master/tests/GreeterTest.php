<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class GreeterTest extends TestCase
{
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