<?php

use Alangustavo\BinanceBacktest\BinanceDateTime;
use Alangustavo\BinanceBacktest\BinanceInterval;
use PHPUnit\Framework\TestCase;

class BinanceDateTimeTest extends TestCase
{
    public function testCreateDateTimeTest()
    {

        $this->assertEquals("2021-05-10 03:00:00", new BinanceDateTime("2021-05-10 03:00:00"));
        $this->assertEquals("2021-05-10 06:00:00", new BinanceDateTime(1620626400));

        $dt = new BinanceDateTime(1623304800000);
        $this->assertEquals("2021-06-10 06:00:00", $dt);
        $this->assertEquals(1623304800000, $dt->getBinaceTimeStamp());
        $this->assertEquals("2021-04-20 14:34:33", new BinanceDateTime(new DateTime("2021-04-20 14:34:33")));
    }

    public function testCompareTwoDateTimesBinance()
    {
        $bdt = new BinanceDateTime("2021-01-01 00:00:00");
        $b = new BinanceDateTime("2021-01-01 00:00:01");
        $c = new BinanceDateTime("2020-12-31 23:59:00");
        $d = new BinanceDateTime("2021-01-01 00:00:00");

        $this->assertTrue($bdt->lessThan($b));
        $this->assertTrue($b->greatThan($bdt));
        $this->assertTrue($c->lessThan($b));
        $this->assertTrue($d->greatThanOrEquals($bdt));
        $this->assertTrue($d->lessThanOrEquals($bdt));
        $this->assertTrue($d->lessThanOrEquals($b));

    }

    public function testAddAndSubTime()
    {
        $bdt = new BinanceDateTime("2021-01-01 00:00:00");
        $bdt->add(new BinanceInterval("5m"), 10);
        $this->assertEquals("2021-01-01 00:50:00", $bdt);
        $bdt->sub(new BinanceInterval("5m"), 3);
        $this->assertEquals("2021-01-01 00:35:00", $bdt);
        $bdt->add(new BinanceInterval("1h"), 3);
        $this->assertEquals("2021-01-01 03:35:00", $bdt);
        $bdt->sub(new BinanceInterval("2h"), 1);
        $this->assertEquals("2021-01-01 01:35:00", $bdt);
        $bdt->add(new BinanceInterval("1d"), 3);
        $this->assertEquals("2021-01-04 01:35:00", $bdt);
        $bdt->sub(new BinanceInterval("12h"), 2);
        $this->assertEquals("2021-01-03 01:35:00", $bdt);

    }
}
