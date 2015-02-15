<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Emanuele Panzeri <thepanz@gmail.com>
 */
require_once __DIR__.'/../../../../lib/Twig/Extensions/Extension/Number.php';

class Twig_Tests_Extension_NumberTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        if (!class_exists('Twig_Extensions_Extension_Number')) {
            self::markTestSkipped('Unable to find class Twig_Extensions_Extension_Number.');
        }
    }

    /**
     * @return array
     */
    public function getDataBytes()
    {
        return array(
            array(null, 'ThisIsAString'),
            array(null, ''),
            array(null, null),
            array('1 B', 1),
            array('1 B', 1, false),
            array('1000 B',  1000),
            array('1.0 KiB', 1000, false),
            array('1.0 kB',  1024),
            array('1.0 KiB', 1024, false),
            array('2.0 kB',  2048),
            array('2.0 KiB', 2048, false),
            array('2.0 kB',  '2048'),
            array('2.0 KiB', '2048', false),
            array('2.4 kB',  2500),
            array('2.5 KiB', 2500, false),
            array('976.6 kB', 1000000),
            array('1.0 MiB',  1000000, false),
            array('1.0 MB',   1048576),
            array('1.0 MiB',  1048576, false),
            array('953.7 MB', 1000000000),
            array('1.0 GiB',  1000000000, false),
            array('1.0 GB',   1073741824),
            array('1.1 GiB',  1073741824, false),
            array('1.0 TB',   1099511627776),
            array('1.1 TiB',  1099511627776, false),
            array('1.0 PB',   1.12589990684263e+15),
            array('1.1 PiB',  1.12589990684263e+15, false),
        );
    }

    /**
     *
     * @dataProvider getDataBytes
     * @param $expected
     * @param $value
     * @param $base2conversion
     */
    public function testBytesConversion($expected, $value, $base2conversion = true)
    {
        $extension = new Twig_Extensions_Extension_Number();
        $this->assertEquals($expected, $extension->format_bytes($value, $base2conversion));
    }

    /**
     * @return array
     */
    public function getDataGrams()
    {
        return array(
            array(null, 'ThisIsAString'),
            array(null, ''),
            array(null, null),
            array(null, 1, -1),
            array('1000.00 Kg', 1000000),
            array('100.00 Kg', 100000),
            array('10.00 Kg', 10000),
            array('1.00 Kg', 1000),
            array('100.00 g', 100),
            array('10.00 g', 10),
            array('1.00 g', 1),
            array('0.00 g', 0),
            array('0.00 g', '0'),
            array('0.00 g', '0.0'),
            array('0.0 g', 0, 1),
            array('0.00 g', 0, 2),
            array('0.000 g', 0, 3),
            array('0 g', 0, 0),
            array('1.00 g', 1),
            array('1.00 g', 1, null, 1),
            array('1.00 mg', 1, null, 1E-3),
            array('1.00 µg', 1, null, 1E-6),
            array('1.00 Kg', 1, null, 1E3),
            array('1.00 g', 1000, null, 1E-3),
            array('1000.00 Kg', 1000, null, 1E3),
            array('1.00 mg', 1000, null, 1E-6),
            array('1.00 g', 1, null),
            array('1 g', 1, 0),
            array('1.0 g', 1, 1),
            array('1.00 g', 1, 2),
            array('1.000 g', 1, 3),
            array('10 g', 10, 0),
            array('10.0 g', 10, 1),
            array('10.00 g', 10, 2),
            array('10.000 g', 10, 3),
            array('100.00 mg', 0.1),
            array('10.00 mg', 0.01),
            array('1.00 mg', 0.001),
            array('100.00 µg', 0.0001),
            array('10.00 µg', 0.00001),
            array('1.00 µg', 0.000001),
            array('10.00 ng', 0.00000001),
            array('1.00 ng', 0.000000001),
            array('10.00 pg', 0.00000000001),
            array('1.00 pg', 0.000000000001),

            // Roundings
            array('2.50 g', 2.5, null, 1),
            array('2.50 mg', 2.5, null, 1E-3),
            array('2.50 Kg', 2.5, null, 1E3),
            array('2.50 Kg', 2.501, null, 1E3),
            array('2.51 Kg', 2.508, null, 1E3),

            // Biased Conversions
            array('1.00 g', 1, null, 1),
            array('1.00 mg', 1, null, 1E-3),
            array('1.00 µg', 1, null, 1E-6),
            array('1.00 Kg', 1, null, 1E3),
            array('1.00 g', 1000, null, 1E-3),
            array('1000.00 Kg', 1000, null, 1E3),
            array('1.00 mg', 1000, null, 1E-6),
        );
    }

    /**
     *
     * @dataProvider getDataGrams
     * @param $expected
     * @param $value
     * @param $decimals
     */
    public function testGramsConversion($expected, $value, $decimals = 2, $unityBias = 1)
    {
        $extension = new Twig_Extensions_Extension_Number();
        $this->assertEquals($expected, $extension->format_grams($value, $decimals, $unityBias));
    }

    /**
     * @return array
     */
    public function getDataMeters()
    {
        return array(
            array(null, 'ThisIsAString'),
            array(null, ''),
            array(null, null),
            array(null, 1, -1),
            array('1000.00 Km', 1000000),
            array('100.00 Km', 100000),
            array('10.00 Km', 10000),
            array('1.00 Km', 1000),
            array('100.00 m', 100),
            array('10.00 m', 10),
            array('1.00 m', 1),
            array('0.00 m', 0.0),
            array('0.00 m', 0),
            array('0.00 m', '0'),
            array('0.00 m', '0.0'),
            array('0.0 m', 0, 1),
            array('0.00 m', 0, 2),
            array('0.000 m', 0, 3),
            array('0 m', 0, 0),
            array('1.00 m', 1),
            array('1.00 m', 1, null),
            array('1 m', 1, 0),
            array('1.0 m', 1, 1),
            array('1.00 m', 1, 2),
            array('1.000 m', 1, 3),
            array('10 m', 10, 0),
            array('10.0 m', 10, 1),
            array('10.00 m', 10, 2),
            array('10.000 m', 10, 3),
            array('10.00 cm', 0.1),
            array('1.00 cm', 0.01),
            array('1.00 mm', 0.001),
            array('100.00 µm', 0.0001),
            array('10.00 µm', 0.00001),
            array('1.00 µm', 0.000001),
            array('10.00 nm', 0.00000001),
            array('1.00 nm', 0.000000001),
            array('10.00 pm', 0.00000000001),
            array('1.00 pm', 0.000000000001),

            // Roundings
            array('2.50 m', 2.5, null, 1),
            array('0.25 cm', 2.5, null, 1E-3),
            array('2.50 Km', 2.5, null, 1E3),
            array('2.50 Km', 2.501, null, 1E3),
            array('2.51 Km', 2.508, null, 1E3),

            // Biased Conversions
            array('1.00 m', 1, null, 1),
            array('1.00 mm', 1, null, 1E-3),
            array('1.00 µm', 1, null, 1E-6),
            array('1.00 Km', 1, null, 1E3),
            array('1.00 m', 1000, null, 1E-3),
            array('1000.00 Km', 1000, null, 1E3),
            array('1.00 mm', 1000, null, 1E-6),
        );
    }

    /**
     *
     * @dataProvider getDataMeters
     * @param $expected
     * @param $value
     * @param $decimals
     */
    public function testMetersConversion($expected, $value, $decimals = 2, $unityBias = 1)
    {
        $extension = new Twig_Extensions_Extension_Number();
        $this->assertEquals($expected, $extension->format_meters($value, $decimals, $unityBias));
    }
}
