<?php

const PRODUCTS_INFO_MAP = [
    1 => [
        'name' => 'Coca-cola',
        'price' => 1.5,
    ],
    2 => [
        'name' => 'Snickers',
        'price' => 1.2,
    ],
    3 => [
        'name' => 'Lay\'s',
        'price' => 2.0,
    ],
];
const ACCEPTABLE_COINS = [0.01, 0.05, 0.1, 0.25, 0.5, 1.0];

echo <<<HELLO_SCREEN_HEADER

    Hello! I'm a vending machine!

    Please, make your choice
    ┌─────────────────────────┐
    │ PLU   Product     Price │
    ├─────────────────────────┤\n
HELLO_SCREEN_HEADER;
foreach (PRODUCTS_INFO_MAP as $plu => $productData) {
    /** @var array{name: string, price: float} $productData */
    printf("    │  %-3s  %-11s %-5.2f │\n", $plu, $productData['name'], $productData['price']);
}
echo <<<HELLO_SCREEN_FOOTER
    └─────────────────────────┘

HELLO_SCREEN_FOOTER;

// Choose a product
do {
    $stdinHandler = fopen('php://stdin', 'r');
    $plu = (int)fgets($stdinHandler);
    flush();
} while (!in_array($plu, [1, 2, 3]));

$productName = PRODUCTS_INFO_MAP[$plu]['name'];
$price = PRODUCTS_INFO_MAP[$plu]['price'];
$deposit = 0.0;
echo 'You\'ve chosen ' . $productName . PHP_EOL
    . printDeposit($deposit, $price) . PHP_EOL
    . 'Insert coin ...' . PHP_EOL;

// Insert coins
do {
    $coinInput = (float)trim(fgets($stdinHandler));
    if (!in_array($coinInput, ACCEPTABLE_COINS)) {
        echo 'Wrong coin. Acceptable coins are: ' . implode(', ', ACCEPTABLE_COINS) . PHP_EOL;
        continue;
    }
    $deposit += $coinInput;
    echo printDeposit($deposit, $price) . PHP_EOL;
} while ($deposit < $price);
echo 'Money accepted.' . PHP_EOL;
sleep(1);

// Give a change out (if so)
if ($deposit > $price) {
    $changeRemained = $deposit - $price;
    echo 'Don\'t forget your change: ' . $changeRemained;

    $changeCoinsMap = [];
    foreach (array_reverse(ACCEPTABLE_COINS) as $maxCoinToCheck) {
        while ($maxCoinToCheck <= $changeRemained) {
            $changeCoinsMap[] = $maxCoinToCheck;
            $changeRemained -= $maxCoinToCheck;
        }
    }
    echo ' (coins: ' . implode(', ', $changeCoinsMap) . ')' . PHP_EOL;
}
for ($i = 0; $i < 3; $i++) {
    usleep(500000);
    echo rand(1, 100) > 50 ? ' KRRR ' : ' WEEEE ';
    usleep(500000);
    echo '...';
}
echo PHP_EOL . 'Take your ' . $productName . '. Bon appetit!' . PHP_EOL;

function printDeposit(float $deposit, float $price): string
{
    return sprintf('Deposit: %01.2f of %01.2f', $deposit, $price);
}
