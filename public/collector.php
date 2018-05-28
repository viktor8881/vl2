<?php


include __DIR__ . '/../vendor/autoload.php';


define("MOEX_URL_CURRENCIES", 'http://iss.moex.com/iss/engines/currency/markets/selt/securities.html?securities=EUR_RUB__TOD,USD000000TOD');


file_put_contents('../data/cache/' . date('dm H-i') .'.html',file_get_contents(MOEX_URL_CURRENCIES));

function pr($val)
{
    echo '<pre>';
    \Doctrine\Common\Util\Debug::dump($val);
    echo '</pre>';
}
