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
            array('1000.00 Kg', 1000000),
            array('100.00 Kg', 100000),
            array('10.00 Kg', 10000),
            array('1.00 Kg', 1000),
            array('100.00 g', 100),
            array('10.00 g', 10),
            array('1.00 g', 1),
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

        );
    }

    /**
     *
     * @dataProvider getDataGrams
     * @param $expected
     * @param $value
     * @param $base2conversion
     */
    public function testGramsConversion($expected, $value, $decimals = 2)
    {
        $extension = new Twig_Extensions_Extension_Number();
        $this->assertEquals($expected, $extension->format_grams($value, $decimals));
    }
}
