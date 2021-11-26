<?php
// $allStringLines = [
//     "London to Dublin = 464",
//     "London to Belfast = 518",
//     "Dublin to Belfast = 141",
// ];
$allStringLines = explode("\n", file_get_contents("9.in.txt"));

$locations = [];

foreach ($allStringLines as $line) {
    $distances = explode(' = ', $line);
    $from_to = explode(' to ', $distances[0]);
    if (!in_array($from_to[0], $locations)) $locations[] = $from_to[0];
    if (!in_array($from_to[1], $locations)) $locations[] = $from_to[1];
}

print_r($locations);