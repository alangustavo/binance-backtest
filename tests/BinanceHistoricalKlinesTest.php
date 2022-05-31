<?php

use Alangustavo\BinanceBacktest\BinanceDateTime;
use Alangustavo\BinanceBacktest\BinanceHistoricalKlines;
use Alangustavo\BinanceBacktest\BinanceInterval;
use Alangustavo\BinanceBacktest\BinanceSymbol;
use PHPUnit\Framework\TestCase;

class BinanceHistoricalKlinesTest extends TestCase
{
    public function testData()
    {
        $ini = new BinanceDateTime("2022-04-01 00:00:00");
        $end = new BinanceDateTime("2022-05-15 00:59:59");
        $bhk = new BinanceHistoricalKlines(new BinanceSymbol("SOL", "USDT"), new BinanceInterval("1h"), $ini, $end);

        $lastClose = end($bhk->closeTimes);
        $this->assertEquals($lastClose->__toString(), $end->__toString());
        $lastOpen  = end($bhk->openTimes);
        $firstOpen = $bhk->openTimes[0];
        $adosc     = trader_adosc($bhk->highs, $bhk->lows, $bhk->closes, $bhk->volumes, 3, 13);
        $this->assertEquals("2022-05-15 00:00:00", "{$lastOpen}");
        $this->assertEquals(-44026.09428849, end($adosc));
        $this->assertEquals("2022-03-11 05:00:00", "{$firstOpen}");
        $this->assertEquals("2022-03-11 05:00:00", "{$firstOpen}");
        $this->assertEquals(1555, $bhk->getLastId());
    }
}
