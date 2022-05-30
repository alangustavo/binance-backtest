<?php
namespace Alangustavo\BinanceBacktest;

use InvalidArgumentException;

/**
 * BinanceInterval class file
 *
 * PHP Version 8.1.4
 *
 * @package  AG
 * @author   Alan Gustavo <alan.gustavo@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 */

final class BinanceInterval
{
    /**
     * BinanceInterval is a string with quantity time and unit time like 1m (for one minute)
     *
     * @var string  "1m", "3m", "5m", "15m", "30m", "1h", "2h", "4h", "6h", "8h",
     *              "12h", "1d", "3d", "1w" or "1M".
     */
    private $BinanceInterval;
    public function __construct(string $BinanceInterval)
    {
        $this->BinanceInterval = $this->checkValidBinanceInterval($BinanceInterval);
    }
    private function checkValidBinanceInterval($BinanceInterval): string | InvalidArgumentException
    {
        $valid_BinanceIntervals = ["1m", "3m", "5m", "15m", "30m", "1h", "2h", "4h", "6h", "8h", "12h", "1d", "3d", "1w", "1M"];
        if (in_array($BinanceInterval, $valid_BinanceIntervals)) {
            return $BinanceInterval;
        } else {
            throw new InvalidArgumentException("Invalid BinanceInterval. Please select a valid BinanceInterval");

        }
    }
    /**
     * Magic Function to Transform object in String
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->BinanceInterval;
    }
    /**
     * Return a time unit in format literal like minutes, hours, days, weeks or months.
     *
     * @return string
     */
    public function getTimeUnit(): string
    {
        $timeUnit = $this->BinanceInterval[-1];
        switch ($timeUnit) {
            case 'm':
                return "minutes";
            case 'h':
                return "hours";
            case 'd':
                return "days";
            case 'w':
                return "weeks";
            case 'M':
                return "months";
            default:
                return 'invalid';
        }
    }
    public function getTimeQuantity(): int
    {
        return intval($this->BinanceInterval);
    }
}
