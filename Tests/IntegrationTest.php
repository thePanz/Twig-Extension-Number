<?php

namespace Pnz\TwigExtensionNumber\Tests;

use Pnz\TwigExtensionNumber\Number;
use Twig\Test\IntegrationTestCase;

/**
 * @covers \Pnz\TwigExtensionNumber\Number
 *
 * @internal
 */
final class IntegrationTest extends IntegrationTestCase
{
    public function getExtensions(): array
    {
        return [
            new Number(),
        ];
    }

    public function getFixturesDir(): string
    {
        return __DIR__.'/Fixtures/';
    }
}
