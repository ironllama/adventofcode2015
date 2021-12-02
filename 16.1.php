<?php

$allStringLines = explode("\n", file_get_contents('16.in.txt'));

$aunts = [];
foreach ($allStringLines as $line) {
    $filtered_line = preg_replace('/[:,]/', '', $line);
    $line_tokens = explode(' ', $filtered_line);

    $aunts[$line_tokens[1]][$line_tokens[2]] = intval($line_tokens[3]);
    $aunts[$line_tokens[1]][$line_tokens[4]] = intval($line_tokens[5]);
    $aunts[$line_tokens[1]][$line_tokens[6]] = intval($line_tokens[7]);
}
// print_r($aunts);

$detected = [
    'children' => 3,
    'cats' => 7,
    'samoyeds' => 2,
    'pomeranians' => 3,
    'akitas' => 0,
    'vizslas' => 0,
    'goldfish' => 5,
    'trees' => 3,
    'cars' => 2,
    'perfumes' => 1,
];

foreach ($aunts as $aunt_num => $aunt_vals) {
    $found = true;
    foreach ($aunt_vals as $key => $value) {
        // if (!in_array($key, $detected) || !$detected[$key] === $value) {
        if ($detected[$key] !== $value) {
            $found = false;
            break;
        }
    }
    if ($found) {
        echo "AUNT: ${aunt_num}".PHP_EOL;
        print_r($aunt_vals);
        echo PHP_EOL;
        break;
    }
}
