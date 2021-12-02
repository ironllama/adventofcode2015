<?php

// $allStringLines = [
//     'Comet can fly 14 km/s for 10 seconds, but then must rest for 127 seconds.',
//     'Dancer can fly 16 km/s for 11 seconds, but then must rest for 162 seconds.',
// ];
$allStringLines = explode("\n", file_get_contents('14.in.txt'));

$reindeer = [];

foreach ($allStringLines as $line) {
    $line_tokens = explode(' ', $line);
    $reindeer[$line_tokens[0]] = [
        'speed' => $line_tokens[3],
        'fly' => $line_tokens[6],
        'period' => $line_tokens[6] + $line_tokens[13],
        ];
}

// print_r($reindeer);
function at_seconds($in_time)
{
    global $reindeer;

    $farthest_distance = 0;
    $farthest_reindeer = '';

    foreach ($reindeer as $name => $val) {
        $integral = intval($in_time / $val['period']);
        $fractional = $in_time % $val['period'];
        if ($fractional > $val['fly']) {
            $fractional = $val['fly'];
        }

        $distance = ($integral * $val['fly'] * $val['speed']) + ($fractional * $val['speed']);

        if ($distance > $farthest_distance) {
            $farthest_distance = $distance;
            $farthest_reindeer = $name;
        }
    }

    echo "FARTHEST ${farthest_distance} ${farthest_reindeer}".PHP_EOL;
}

// at_seconds(1000);
at_seconds(2503);
