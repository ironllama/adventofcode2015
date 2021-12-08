<?php

$limit = 130;
$presents = 0;
$house = 1;
while ($presents < $limit) {
    echo "\tH: {$house}".PHP_EOL;
    $presents = 0;
    $factor_limit = floor($house / 2);

    if ($house !== 1) {
        foreach (range(1, $factor_limit) as $factor) {
            echo "\tTEST: {$factor}".PHP_EOL;
            if ($house % $factor === 0) {
                echo "\tFAC: {$factor}".PHP_EOL;
                // $score += $factor * 10;
                $presents += $factor * 10;
            }
        }
    }

    echo "\tP: {$presents}".PHP_EOL;
    $house += 1;
}
