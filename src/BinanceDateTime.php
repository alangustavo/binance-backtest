<?php
namespace Alangustavo\BinanceBacktest;

use Alangustavo\BinanceBacktest\BinanceInterval;
use DateInterval;
use DateTime;

/**
 * BinanceDateTime class file
 *
 * PHP Version 8.1.4
 *
 * @package  alangustavo/binance-backtest
 * @author   Alan Gustavo <alan.gustavo@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 */

class BinanceDateTime
{
    private $dateTime;
    /**
     * Create a BinanceDateTime by integer, string or DateTime.
     * $bdt = new BinanceDateTime("2022-05-01 00:00:00") or
     * $bdt = new BinanceDateTime(new DateTime("2022-05-01 00:00:00"))
     * $bdt = new BinanceDateTime(1620626400)
     * $bdt = new BinanceDateTime(1623304800000)
     */
    public function __construct(int | string | DateTime $dateTime)
    {
        if ($dateTime instanceof DateTime) {
            $this->dateTime = $dateTime;
        } elseif (is_string($dateTime)) {
            $this->dateTime = new DateTime($dateTime);
        } elseif (is_int($dateTime)) {
            $this->dateTime = new DateTime();
            if ($dateTime < 9999999999) {
                $this->dateTime->setTimestamp(intval($dateTime));
            } else {
                $this->dateTime->setTimestamp(intval($dateTime / 1000));
            }
        }
    }
    /**
     * Magic Method - Return a date in string format Y-m-d H:i:s eg. 2021-02-21 19:03:22
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->dateTime->format('Y-m-d H:i:s');
    }

    /**
     * Return a Integer TimeStamp Binance (normal TimeStamp * 1000)
     *
     * @return integer
     */
    public function getBinaceTimeStamp(): int
    {
        return $this->dateTime->getTimestamp() * 1000;
    }

    /**
     * Return a DateTime Object from Binance
     *
     * @return DateTime
     */
    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    /**
     * Return true/false if this is greatThanOrEquals a BinanceDateTime
     *
     * @param BinanceDateTime $bdt
     * @return boolean
     */
    public function greatThanOrEquals(BinanceDateTime $bdt): bool
    {
        return $this->getBinaceTimeStamp() >= $bdt->getBinaceTimeStamp();
    }
    /**
     * Return true/false if this is lessThanOrEquals a BinanceDateTime
     *
     * @param BinanceDateTime $bdt
     * @return boolean
     */
    public function lessThanOrEquals(BinanceDateTime $bdt): bool
    {
        return $this->getBinaceTimeStamp() <= $bdt->getBinaceTimeStamp();
    }
    /**
     * Return true/false if this is greatThan a BinanceDateTime
     *
     * @param BinanceDateTime $bdt
     * @return boolean
     */
    public function greatThan(BinanceDateTime $bdt): bool
    {
        return $this->getBinaceTimeStamp() > $bdt->getBinaceTimeStamp();
    }
    /**
     * Return true/false if this is lessThan a BinanceDateTime
     *
     * @param BinanceDateTime $bdt
     * @return boolean
     */
    public function lessThan(BinanceDateTime $bdt): bool
    {
        return $this->getBinaceTimeStamp() < $bdt->getBinaceTimeStamp();
    }

    /**
     * Returns $this object, adding an amount of intervals
     *
     * @param BinanceInterval $BinanceInterval
     * @param integer $quantity
     * @return BinanceDateTime
     */
    public function add(BinanceInterval $BinanceInterval, int $quantity = 1000): BinanceDateTime
    {
        $quantity = $BinanceInterval->getTimeQuantity() * $quantity;
        $str = "{$quantity} {$BinanceInterval->getTimeUnit()}";
        $this->dateTime->add(DateInterval::createFromDateString($str));
        return $this;
    }
    /**
     * Returns $this object, subtracting an amount of intervals
     *
     * @param BinanceInterval $BinanceInterval
     * @param integer $quantity
     * @return BinanceDateTime
     */
    public function sub(BinanceInterval $BinanceInterval, int $quantity = 1000): BinanceDateTime
    {
        $quantity = $BinanceInterval->getTimeQuantity() * $quantity;
        $str = "{$quantity} {$BinanceInterval->getTimeUnit()}";
        $this->dateTime->sub(DateInterval::createFromDateString($str));
        return $this;
    }

}
