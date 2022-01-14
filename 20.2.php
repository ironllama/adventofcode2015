<?php

$limit = 29000000;

$i = 0;
$total_presents = 0;
while ($total_presents < $limit) {
    $total_presents = 0;
    for ($elf = 1; $elf <= $i; ++$elf) {
        if (0 == $i % $elf && $i <= ($elf * 50)) {
            $total_presents += $elf * 11;
        }
    }
    echo "House {$i} got {$total_presents}.\n";
    $i += (2 * 3 * 5 * 7);  // 11 produced too large of a gap.
}

// 720720 // HIGH
