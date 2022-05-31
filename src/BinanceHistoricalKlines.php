<?php
namespace Alangustavo\BinanceBacktest;

use Alangustavo\BinanceBacktest\BinanceDateTime;
use Alangustavo\BinanceBacktest\BinanceInterval;
use Alangustavo\BinanceBacktest\BinanceSymbol;
use DateTime;

/**
 * BinanceHistoricalKlines class file
 *
 * PHP Version 8.1.4
 *
 * @package  alangustavo/binance-backtest
 * @author   Alan Gustavo <alan.gustavo@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 */

class BinanceHistoricalKlines
{
    /**
     * OpenTimes in Interval
     *
     * @var BinanceDateTime[]
     */
    public $openTimes = [];
    /**
     * Open Prices in Interval
     *
     * @var float[]
     */
    public $opens = [];
    /**
     * High Prices in Interval
     *
     * @var float[]
     */
    public $highs = [];
    /**
     * Low Prices in Interval
     *
     * @var float[]
     */
    public $lows = [];
    /**
     * Close Prices in Interval
     *
     * @var float[]
     */
    public $closes = [];
    /**
     * Volume in interval
     *
     * @var float[]
     */
    public $volumes = [];
    /**
     * Close Times in Interval
     *
     * @var BinanceDateTime[]
     */
    public $closeTimes = [];
    /**
     * Quoted Volumes
     *
     * @var float[]
     */
    public $quoteVolumes = [];
    /**
     * Number of trafes
     *
     * @var int[]
     */
    public $numberOfTradess = [];
    /**
     * Buy Base Asset Volumes
     *
     * @var float[]
     */
    public $buyBaseAssetVolumes = [];
    /**
     * Buy Quoted Asset Volumes
     *
     * @var float[]
     */
    public $buyQuoteAssetVolumes = [];
    /**
     * BinanceDateTime for start BackTest
     *
     * @var BinanceDateTime
     */
    private $ini;
    /**
     * BinanceDateTime for end BackTest
     *
     * @var BinanceDateTime
     */
    private $end;
    /**
     * Symbol Binance
     *
     * @var BinanceSymbol
     */
    private $symbol;
    /**
     * BinanceInterval
     *
     * @var BinanceInterval
     */
    private $BinanceInterval;
    /**
     * Create a WorkSheet with all historical klines from the $ini and $end. Start with 499 back
     * registers to calculate correctly trades indicators.
     * @param BinanceSymbol $symbol
     * @param BinanceInterval $BinanceInterval
     * @param BinanceDateTime $ini
     * @param BinanceDateTime|null $end if null $end is now.
     */
    public function __construct(BinanceSymbol $symbol, BinanceInterval $BinanceInterval, BinanceDateTime $ini, BinanceDateTime $end = null)
    {
        $this->ini             = $ini;
        $this->end             = $end == null ? new BinanceDateTime(new DateTime("now")) : $end;
        $this->symbol          = $symbol;
        $this->BinanceInterval = $BinanceInterval;
        $this->loadHistoricalDataFromBinance();

    }
    /**
     * https: //binance-docs.github.io/apidocs/spot/en/#compressed-aggregate-trades-list
     * [
     *  1499040000000,      // Open time
     *  "0.01634790",       // Open
     *  "0.80000000",       // High
     *  "0.01575800",       // Low
     *  "0.01577100",       // Close
     *  "148976.11427815",  // Volume
     *  1499644799999,      // Close time
     *  "2434.19055334",    // Quote asset volume
     *  308,                // Number of trades
     *  "1756.87402397",    // Taker buy base asset volume
     *  "28.46694368",      // Taker buy quote asset volume
     *  "17928899.62484339" // Ignore.
     * ]
     * @return void
     */
    public function add($k)
    {

        $ot = new BinanceDateTime($k[0]);
        if ( # This is for first add nothing to test
            count($this->openTimes) == 0 or
            # Test if new OpenTime is Great the Last OpenTime and not Great to the end.
            ($ot->greatThan(end($this->openTimes)) and $this->end->greatThan($ot))) {
            $this->openTimes[]            = $ot;
            $this->opens[]                = floatval($k[1]);
            $this->closes[]               = floatval($k[4]);
            $this->highs[]                = floatval($k[2]);
            $this->lows[]                 = floatval($k[3]);
            $this->volumes[]              = floatval($k[5]);
            $this->closeTimes[]           = new BinanceDateTime($k[6]);
            $this->quoteVolumes[]         = floatval($k[7]);
            $this->numberOfTradess[]      = intval($k[8]);
            $this->buyBaseAssetVolumes[]  = floatval($k[9]);
            $this->buyQuoteAssetVolumes[] = floatval($k[10]);
        }
    }

    /**
     * Get Last Id from arrays
     *
     * @return integer
     */
    public function getLastId(): int
    {
        return array_key_last($this->closes);
    }

    /**
     * This function load Historical Data from Binance. In order to calculate some traders indicators you
     * need 500 back registers (like Binance indicators). The first register is 499 backward register and
     * the end register is equals endDate or now.
     *
     * @return void
     */
    public function loadHistoricalDataFromBinance()
    {
        do {
            $this->ini->add($this->BinanceInterval, 500);
            if ($this->ini->greatThanOrEquals($this->end)) {
                $this->ini = $this->end;
            }
            $url = "https://api.binance.com/api/v3/klines?symbol={$this->symbol}";
            $url .= "&interval={$this->BinanceInterval}";
            $url .= "&endTime={$this->ini->getBinaceTimeStamp()}&limit=1000";
            $result = file_get_contents($url);
            $data   = json_decode($result);

            foreach ($data as $k) {
                $this->add($k);
            }

        } while ($this->ini->lessThan($this->end));
    }
}
