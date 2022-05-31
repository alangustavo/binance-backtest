<?php

use Alangustavo\BinanceBacktest\BinanceSymbol;
use PHPUnit\Framework\TestCase;

class BinanceSymbolTest extends TestCase
{
    public function testSymbol()
    {
        $bs = new BinanceSymbol("sol", "usdt");
        $this->assertEquals("SOLUSDT", $bs);
    }
}
