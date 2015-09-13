<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This code has been taken from BrasilianFOS Extension Bundle
 *
 * @author Emanuele Panzeri <thepanz@gmail.com>
 * @author Paulo Ribeiro <paulo@duocriativa.com.br>
 * @package Twig-Extension-Number
 * @subpackage Twig-extensions
 */
class Twig_Extensions_Extension_Number extends Twig_Extension
{
    const DEFAULT_DECIMALS = 2;
    const UNITY_GRAM = 'g';
    const UNITY_METER = 'm';
    protected static $unities = array(
        self::UNITY_GRAM => array(
            '3'   => 'K', // kilogram
            // '2'     => 'H',
            // '1'     => 'Da',
            '0'   => '',
            // '-1'    => 'd', // decigram
            // '-2'  => 'c',  // centigram
            '-3'  => 'm',  // milligram
            '-6'  => 'µ',  // microgram (mcg)
            '-9'  => 'n',  // nanogram
            '-12' => 'p',  // picogram
        ),
        self::UNITY_METER => array(
            '3'   => 'K', // kilometer
            // '2'     => 'H',
            // '1'     => 'Da',
            '0'   => '',
            // '-1'    => 'd', // decimeter
            '-2'  => 'c',  // centimeter
            '-3'  => 'm',  // millimiter
            '-6'  => 'µ',  // micrometer
            '-9'  => 'n',  // nanometer
            '-12' => 'p',  // picometer
        ),
    );

    /**
     * Returns a list of filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('format_bytes',  array($this, 'format_bytes')),
            new Twig_SimpleFilter('format_grams',  array($this, 'format_grams')),
            new Twig_SimpleFilter('format_meters',  array($this, 'format_meters')),
        );
    }

    /**
     * Name of this extension
     *
     * @return string
     */
    public function getName()
    {
        return 'Number';
    }

    /**
     * @param $number
     * @return bool
     */
    protected function is_valid_value($number)
    {
        return is_numeric($number);
    }

    /**
     * @param  int    $search
     * @param  string $unity
     * @return int    The new exponential to use
     */
    protected function get_nearest_exp($search, $unity)
    {
        $exps = array_keys(self::$unities[$unity]);
        $closest = array_pop($exps);

        foreach ($exps as $value) {
            if ($value == $search || $search > $value) {
                return $value;
            }

            if (abs($search - $closest) > abs($value - $search)) {
                $closest = $value;
            }
        }

        return $closest;
    }

    /**
     * @param $exp
     * @param $unity
     * @return string
     */
    protected function get_unity_prefix($exp, $unity)
    {
        return self::$unities[$unity][$exp];
    }

    /**
     * @param  int|float|string $number
     * @param  int $decimals
     * @param  string $unity
     * @param int $unityBias
     * @return string
     */
    protected function format_unity($number, $decimals, $unity, $unityBias = 1)
    {
        if (! $this->is_valid_value($number) || $decimals < 0) {
            return;
        }
        if (is_null($decimals)) {
            $decimals = self::DEFAULT_DECIMALS;
        }

        if ($unityBias !== 1 && $unityBias !== 0 && !is_null($unityBias)) {
            $number *= $unityBias;
        }

        $exp = ($number == 0) ? 0 : intval(log10(abs($number)));
        $exp = $this->get_nearest_exp($exp, $unity);
        $pre = $this->get_unity_prefix($exp, $unity);
        $value = $number / pow(10, $exp);

        return sprintf('%.'.$decimals.'f %s'.$unity, $value, $pre);
    }

    /**
     * @param  int|float|string $number
     * @param  int              $decimals
     * @return string
     */
    public function format_meters($number, $decimals = 2, $unityBias = 1)
    {
        return $this->format_unity($number, $decimals, self::UNITY_METER, $unityBias);
    }

    /**
     * @param  int|float|string $number
     * @param  int              $decimals
     * @return string
     */
    public function format_grams($number, $decimals = 2, $unityBias = 1)
    {
        return $this->format_unity($number, $decimals, self::UNITY_GRAM, $unityBias);
    }

    /**
     * Filter for converting bytes to a human-readable format, as Unix command "ls -h" does.
     *
     * @param string|int $number          A string or integer number value to format.
     * @param bool       $base2conversion Defines if the conversion has to be strictly performed as binary values or
     *                                    by using a decimal conversion such as 1 KByte = 1000 Bytes.
     *
     * @return string The number converted to human readable representation.
     * @todo: Use Intl-based translations to deal with "11.4" conversion to "11,4" value
     */
    public function format_bytes($number, $base2conversion = true)
    {
        if (! $this->is_valid_value($number)) {
            return;
        }

        $unit = $base2conversion ? 1024 : 1000;
        if ($number < $unit) {
            return $number.' B';
        }
        $exp = intval((log($number) / log($unit)));
        $pre = ($base2conversion ? 'kMGTPE' : 'KMGTPE');
        $pre = $pre[$exp - 1].($base2conversion ? '' : 'i');

        return sprintf('%.1f %sB', $number / pow($unit, $exp), $pre);
    }
}
