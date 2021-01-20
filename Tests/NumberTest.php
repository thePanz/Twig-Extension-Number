<?php

namespace Pnz\TwigNumber\Tests;

use PHPUnit\Framework\TestCase;
use Pnz\TwigExtensionNumber\Number;

/**
 * @covers \Pnz\TwigExtensionNumber\Number
 *
 * @internal
 */
final class NumberTest extends TestCase
{
    public function getDataBytes(): array
    {
        return [
            ['', 'ThisIsAString'],
            ['', ''],
            ['', null],
            ['1 B', 1],
            ['1 B', 1, false],
            ['1000 B',  1000],
            ['1.0 KiB', 1000, false],
            ['1.0 kB',  1024],
            ['1.0 KiB', 1024, false],
            ['2.0 kB',  2048],
            ['2.0 KiB', 2048, false],
            ['2.0 kB',  '2048'],
            ['2.0 KiB', '2048', false],
            ['2.4 kB',  2500],
            ['2.5 KiB', 2500, false],
            ['976.6 kB', 1000000],
            ['1.0 MiB', 1000000, false],
            ['1.0 MB',  1048576],
            ['1.0 MiB', 1048576, false],
            ['953.7 MB', 1000000000],
            ['1.0 GiB', 1000000000, false],
            ['1.0 GB',  1073741824],
            ['1.1 GiB', 1073741824, false],
            ['1.0 TB',  1099511627776],
            ['1.1 TiB', 1099511627776, false],
            ['1.0 PB',  1.12589990684263e+15],
            ['1.1 PiB', 1.12589990684263e+15, false],
        ];
    }

    /**
     * @dataProvider getDataBytes
     *
     * @param float|int|string $value
     */
    public function testBytesConversion(string $expected, $value, bool $base2conversion = true): void
    {
        $extension = new Number();
        static::assertSame($expected, $extension->formatBytes($value, $base2conversion));
    }

    public function getDataGrams(): array
    {
        return [
            ['', 'ThisIsAString'],
            ['', ''],
            ['', null],
            ['', 1, -1],
            ['1000.00 Kg', 1000000],
            ['100.00 Kg', 100000],
            ['10.00 Kg', 10000],
            ['1.00 Kg', 1000],
            ['100.00 g', 100],
            ['10.00 g', 10],
            ['1.00 g', 1],
            ['0.00 g', 0],
            ['0.00 g', '0'],
            ['0.00 g', '0.0'],
            ['0.0 g', 0, 1],
            ['0.00 g', 0, 2],
            ['0.000 g', 0, 3],
            ['0 g', 0, 0],
            ['1.00 g', 1],
            ['1.00 g', 1, 2],
            ['1.00 g', 1, 2, 1],
            ['1.00 mg', 1, 2, 1E-3],
            ['1.00 µg', 1, 2, 1E-6],
            ['1.00 Kg', 1, 2, 1E3],
            ['1.00 g', 1000, 2, 1E-3],
            ['1000.00 Kg', 1000, 2, 1E3],
            ['1.00 mg', 1000, 2, 1E-6],
            ['1 g', 1, 0],
            ['1.0 g', 1, 1],
            ['1.00 g', 1, 2],
            ['1.000 g', 1, 3],
            ['10 g', 10, 0],
            ['10.0 g', 10, 1],
            ['10.00 g', 10, 2],
            ['10.000 g', 10, 3],
            ['100.00 mg', 0.1],
            ['10.00 mg', 0.01],
            ['1.00 mg', 0.001],
            ['100.00 µg', 0.0001],
            ['10.00 µg', 0.00001],
            ['1.00 µg', 0.000001],
            ['10.00 ng', 0.00000001],
            ['1.00 ng', 0.000000001],
            ['10.00 pg', 0.00000000001],
            ['1.00 pg', 0.000000000001],
            // Roundings
            ['2.50 g', 2.5, 2, 1],
            ['2.50 mg', 2.5, 2, 1E-3],
            ['2.50 Kg', 2.5, 2, 1E3],
            ['2.50 Kg', 2.501, 2, 1E3],
            ['2.51 Kg', 2.508, 2, 1E3],
            // Biased Conversions
            ['1.00 g', 1, 2, 1],
            ['1.00 mg', 1, 2, 1E-3],
            ['1.00 µg', 1, 2, 1E-6],
            ['1.00 Kg', 1, 2, 1E3],
            ['1.00 g', 1000, 2, 1E-3],
            ['1000.00 Kg', 1000, 2, 1E3],
            ['1.00 mg', 1000, 2, 1E-6],
            // Negative numbers
            ['-1.00 g', 1, 2, -1],
            ['-1.20 g', 1, 2, -1.2],
            ['-10.00 mg', 1, 2, -1E-2],
            ['-1.00 mg', 1, 2, -1E-3],
            ['-1.00 Kg', 1, 2, -1E3],
            ['-1000.00 Kg', 1, 2, -1E6],
        ];
    }

    /**
     * @dataProvider getDataGrams
     *
     * @param float|int|string $value
     * @param float|int        $unityBias
     */
    public function testGramsConversion(string $expected, $value, int $decimals = 2, $unityBias = 1): void
    {
        $extension = new Number();
        static::assertSame($expected, $extension->formatGrams($value, $decimals, $unityBias));
    }

    public function getDataMeters(): array
    {
        return [
            ['', 'ThisIsAString'],
            ['', ''],
            ['', null],
            ['', 1, -1],
            ['1000.00 Km', 1000000],
            ['100.00 Km', 100000],
            ['10.00 Km', 10000],
            ['1.00 Km', 1000],
            ['100.00 m', 100],
            ['10.00 m', 10],
            ['1.00 m', 1],
            ['0.00 m', 0.0],
            ['0.00 m', 0],
            ['0.00 m', '0'],
            ['0.00 m', '0.0'],
            ['0.0 m', 0, 1],
            ['0.00 m', 0, 2],
            ['0.000 m', 0, 3],
            ['0 m', 0, 0],
            ['1.00 m', 1],
            ['1 m', 1, 0],
            ['1.0 m', 1, 1],
            ['1.00 m', 1, 2],
            ['1.000 m', 1, 3],
            ['10 m', 10, 0],
            ['10.0 m', 10, 1],
            ['10.00 m', 10, 2],
            ['10.000 m', 10, 3],
            ['10.00 cm', 0.1],
            ['1.00 cm', 0.01],
            ['1.00 mm', 0.001],
            ['100.00 µm', 0.0001],
            ['10.00 µm', 0.00001],
            ['1.00 µm', 0.000001],
            ['10.00 nm', 0.00000001],
            ['1.00 nm', 0.000000001],
            ['10.00 pm', 0.00000000001],
            ['1.00 pm', 0.000000000001],

            // Roundings
            ['2.50 m', 2.5, 2, 1],
            ['0.25 cm', 2.5, 2, 1E-3],
            ['2.50 Km', 2.5, 2, 1E3],
            ['2.50 Km', 2.501, 2, 1E3],
            ['2.51 Km', 2.508, 2, 1E3],

            // Biased Conversions
            ['1.00 m', 1, 2, 1],
            ['1.00 mm', 1, 2, 1E-3],
            ['1.00 µm', 1, 2, 1E-6],
            ['1.00 Km', 1, 2, 1E3],
            ['1.00 m', 1000, 2, 1E-3],
            ['1000.00 Km', 1000, 2, 1E3],
            ['1.00 mm', 1000, 2, 1E-6],

            // Negative numbers
            ['-1.00 m', 1, 2, -1],
            ['-1.20 m', 1, 2, -1.2],
            ['-1.00 mm', 1, 2, -1E-3],
            ['-1.00 cm', 1, 2, -1E-2],
            ['-1.00 Km', 1, 2, -1E3],
            ['-1000.00 Km', 1, 2, -1E6],
        ];
    }

    /**
     * @dataProvider getDataMeters
     *
     * @param float|int|string $value
     * @param float|int        $unityBias
     */
    public function testMetersConversion(string $expected, $value, int $decimals = 2, $unityBias = 1): void
    {
        $extension = new Number();
        static::assertSame($expected, $extension->formatMeters($value, $decimals, $unityBias));
    }
}
