<?php

$limit = 130;
// $limit = 29000000;

// $presents = 0;
// $house = 1;
// $presents = $limit;
// $house = $limit;
// 250009 221761 1582340 665280
// Try multiples of 8?

// $presents = 0;
// $house = 1;
// while ($presents < $limit && $house > 0) {
//     // while ($presents > ($limit / 10)) {
//     // echo "H: {$house}".PHP_EOL;
//     $presents = 0;
//     $factor_limit = floor($house / 2);

//     if (1 !== $house) {
//         foreach (range(1, $factor_limit) as $factor) {
//             // echo "\tTEST: {$factor}".PHP_EOL;
//             if (0 === $house % $factor) {
//                 // echo "\tFAC: {$factor}".PHP_EOL;
//                 $presents += $factor * 10;
//                 // $presents += $factor;
//             }
//         }
//     }

//     $presents += $house * 10;  // Self

//     echo "H: {$house} P: {$presents}".PHP_EOL;
//     // ++$house;
//     // --$house;
//     $house += 64;
// }

// echo "HOUSE: {$house} PRESENTS: ".($presents * 10).PHP_EOL;

// $new_limit = $limit / 10;
// $half = $new_limit / 2;
// $shrunk = $new_limit;
// foreach (range(1, $half) as $factor) {
//     if (0 === $new_limit % $factor) {
//         $shrunk -= $factor;
//         echo "FOUND: {$factor}\n";
//     }
// }

$new_limit = $limit / 10;
$running_limit = $new_limit;  // Count the first house, since 1 is an odd case.
$curr_house = 1;
while ($running_limit > 0) {
    if (0 == $curr_house % $new_limit) {
        $running_limit -= $curr_house;
    }
    ++$curr_house;  // Only count even factors.
}

echo "NEW: {$curr_house}\n";
