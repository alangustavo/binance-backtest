<?php

use Alangustavo\BinanceBacktest\BinanceInterval;
use PHPUnit\Framework\TestCase;

class BinanceIntervalTest extends TestCase
{
    protected $valid_BinanceIntervals = ["1m", "3m", "5m", "15m", "30m", "1h", "2h", "4h", "6h", "8h", "12h", "1d", "3d", "1w", "1M"];

    public function testIfIsInvalidBinanceInterval()
    {
        $this->expectException(InvalidArgumentException::class);
        new BinanceInterval("9m");
    }

    public function testTimeUnitReturn()
    {
        $BinanceInterval = new BinanceInterval("5m");
        $this->assertEquals("minutes", $BinanceInterval->getTimeUnit());
        $BinanceInterval = new BinanceInterval("1M");
        $this->assertEquals("months", $BinanceInterval->getTimeUnit());
        $BinanceInterval = new BinanceInterval("12h");
        $this->assertEquals("hours", $BinanceInterval->getTimeUnit());
        $BinanceInterval = new BinanceInterval("1d");
        $this->assertEquals("days", $BinanceInterval->getTimeUnit());
        $BinanceInterval = new BinanceInterval("1w");
        $this->assertEquals("weeks", $BinanceInterval->getTimeUnit());

    }

    public function testTimeQuantity()
    {
        $BinanceInterval = new BinanceInterval("5m");
        $this->assertEquals(5, $BinanceInterval->getTimeQuantity());
        $BinanceInterval = new BinanceInterval("1M");
        $this->assertEquals(1, $BinanceInterval->getTimeQuantity());
        $BinanceInterval = new BinanceInterval("12h");
        $this->assertEquals(12, $BinanceInterval->getTimeQuantity());
        $BinanceInterval = new BinanceInterval("3d");
        $this->assertEquals(3, $BinanceInterval->getTimeQuantity());
        $BinanceInterval = new BinanceInterval("4h");
        $this->assertEquals(4, $BinanceInterval->getTimeQuantity());
    }
}
