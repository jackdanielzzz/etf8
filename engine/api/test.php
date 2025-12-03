<?php
error_reporting(E_ALL & ~E_DEPRECATED);

$config = Finnhub\Configuration::getDefaultConfiguration()->setApiKey('token', 'd0q4k0pr01qmj4ni1l5gd0q4k0pr01qmj4ni1l60');
$client = new Finnhub\Api\DefaultApi(
    new GuzzleHttp\Client(),
    $config
);

try {
// Список тикеров
    $tickers = [
        'VOO', 'SPY', 'IVV', 'QQQ', 'VTI', 'ARKK', 'ARKF', 'BITO', 'IBIT', 'GBTC', 'BLOK', 'VUG', 'SMH',
        'BINANCE:BTCUSDT', 'BINANCE:ETHUSDT', 'BINANCE:SOLUSDT', 'BINANCE:XRPUSDT', 'BINANCE:LTCUSDT',
        'BINANCE:USDCUSDT', 'BINANCE:TONUSDT'
    ];

    // Заголовок
    echo "📊 Котировки активов на " . date('d.m.Y H:i') . " (CEST)<br>";
    echo "══════════════════════════════════════════════════════<br>";

    // Получение котировок для каждого тикера
    foreach ($tickers as $ticker) {
        try {
            $quote = $client->quote($ticker);

            // Проверка, вернул ли API данные
            if ($quote->getC() === null) {
                echo "⚠️ Нет данных для $ticker<br>";
                continue;
            }

            // Форматированный вывод
            echo "📌 $ticker<br>";
            echo "──────────────────<br>";
            echo "📈 Текущая цена: \t$" . number_format($quote->getC(), 2) . "<br>";
            echo "📈 % изменения: \t" . ($quote->getDp() >= 0 ? '+' : '') . number_format($quote->getDp(), 2) . "%<br>";
            echo "══════════════════<br><br>";
        } catch (Exception $e) {
            echo "❌ Ошибка для $ticker: " . $e->getMessage() . "<br>";
            //echo "Тело ответа: " . $e->getResponseBody() . "<br><br>";
        }
    }

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "<br>";
    echo 'Response body: ', $e->getResponseBody(), "<br>";
}
?>