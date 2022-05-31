<?php
namespace Alangustavo\BinanceBacktest;

/**
 * BinanceSynbol class file
 *
 * PHP Version 8.1.4
 *
 * @package  alangustavo/binance-backtest
 * @author   Alan Gustavo <alan.gustavo@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 */

class BinanceSymbol
{
    private $coin;
    private $base;
    public function __construct($coin, $base = null)
    {
        $this->coin = strtoupper($coin);
        $this->base = strtoupper($base);
    }

    public function __toString()
    {
        return "{$this->coin}{$this->base}";
    }
}
