<?php

namespace Pnz\TwigExtensionNumber;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class Number extends AbstractExtension
{
    private const DEFAULT_DECIMALS = 2;
    private const UNITY_GRAM = 'g';
    private const UNITY_METER = 'm';
    private static $unities = [
        self::UNITY_GRAM => [
            '3' => 'K', // kilogram
            // '2'     => 'H',
            // '1'     => 'Da',
            '0' => '',
            // '-1'    => 'd', // decigram
            // '-2'  => 'c',  // centigram
            '-3' => 'm',  // milligram
            '-6' => 'µ',  // microgram (mcg)
            '-9' => 'n',  // nanogram
            '-12' => 'p',  // picogram
        ],
        self::UNITY_METER => [
            '3' => 'K', // kilometer
            // '2'     => 'H',
            // '1'     => 'Da',
            '0' => '',
            // '-1'    => 'd', // decimeter
            '-2' => 'c',  // centimeter
            '-3' => 'm',  // millimiter
            '-6' => 'µ',  // micrometer
            '-9' => 'n',  // nanometer
            '-12' => 'p',  // picometer
        ],
    ];

    public function getFilters(): array
    {
        return [
            new TwigFilter('format_bytes', [$this, 'formatBytes']),
            new TwigFilter('format_grams', [$this, 'formatGrams']),
            new TwigFilter('format_meters', [$this, 'formatMeters']),
        ];
    }

    /**
     * @param float|int|string $number
     * @param float|int        $unityBias
     */
    public function formatMeters($number, int $decimals = 2, $unityBias = 1): string
    {
        return $this->formatUnity($number, $decimals, self::UNITY_METER, $unityBias);
    }

    /**
     * @param float|int|string $number
     * @param float|int        $unityBias
     */
    public function formatGrams($number, int $decimals = 2, $unityBias = 1): string
    {
        return $this->formatUnity($number, $decimals, self::UNITY_GRAM, $unityBias);
    }

    /**
     * Filter for converting bytes to a human-readable format, as Unix command "ls -h" does.
     *
     * @param int|string $number          a string or integer number value to format
     * @param bool       $base2conversion defines if the conversion has to be strictly performed as binary values or
     *                                    by using a decimal conversion such as 1 KByte = 1000 Bytes
     *
     * @return string the number converted to human readable representation
     * @todo: Use Intl-based translations to deal with "11.4" conversion to "11,4" value
     */
    public function formatBytes($number, bool $base2conversion = true): string
    {
        if (!$this->isValidValue($number)) {
            return '';
        }

        $unit = $base2conversion ? 1024 : 1000;
        if ($number < $unit) {
            return $number.' B';
        }
        $exp = (int) (log($number) / log($unit));
        $pre = ($base2conversion ? 'kMGTPE' : 'KMGTPE');
        $pre = $pre[$exp - 1].($base2conversion ? '' : 'i');

        return sprintf('%.1f %sB', $number / ($unit ** $exp), $pre);
    }

    /**
     * @param float|int|string $number
     * @param float|int        $unityBias
     */
    protected function formatUnity($number, int $decimals, string $unity, $unityBias = 1): string
    {
        if ($decimals < 0 || !$this->isValidValue($number)) {
            return '';
        }
        if (null === $decimals) {
            $decimals = self::DEFAULT_DECIMALS;
        }

        if (1 !== $unityBias && 0 !== $unityBias && null !== $unityBias) {
            $number *= $unityBias;
        }

        $exp = (0 === $number) ? 0 : (int) log10(abs($number));
        $exp = $this->getNearestExp($exp, $unity);
        $pre = $this->getUnityPrefix($exp, $unity);
        $value = $number / (10 ** $exp);

        return sprintf('%.'.$decimals.'f %s'.$unity, $value, $pre);
    }

    /**
     * @param mixed $number
     */
    private function isValidValue($number): bool
    {
        return is_numeric($number);
    }

    /**
     * @return int The new exponential to use
     */
    private function getNearestExp(int $search, string $unity): int
    {
        $exps = array_keys(self::$unities[$unity]);
        $closest = array_pop($exps);

        foreach ($exps as $value) {
            if ((int) $value === $search || $search > $value) {
                return $value;
            }

            if (abs($search - $closest) > abs($value - $search)) {
                $closest = $value;
            }
        }

        return $closest;
    }

    private function getUnityPrefix(int $exp, string $unity): string
    {
        return self::$unities[$unity][(string) $exp];
    }
}
