<?php

// $allStringLines = [
//     'London to Dublin = 464',
//     'London to Belfast = 518',
//     'Dublin to Belfast = 141',
// ];
$allStringLines = explode("\n", file_get_contents('9.in.txt'));

$locations = [];

foreach ($allStringLines as $line) {
    $distances = explode(' = ', $line);
    $from_to = explode(' to ', $distances[0]);

    // Write one direction.
    if (!array_key_exists($from_to[0], $locations)) {
        $locations[$from_to[0]] = [$from_to[1] => $distances[1]];
    } else {
        $locations[$from_to[0]][$from_to[1]] = $distances[1];
    }

    // Write the other direction, too, for easy lookup.
    if (!array_key_exists($from_to[1], $locations)) {
        $locations[$from_to[1]] = [$from_to[0] => $distances[1]];
    } else {
        $locations[$from_to[1]][$from_to[0]] = $distances[1];
    }
}
// print_r($locations);
// print_r(array_keys($locations));

$all_locations = array_keys($locations);
$num_all_locations = count($all_locations);
$all_combos = [];
$longest = ['path' => '', 'dist' => 0];

function getNext($visited, $to_visit, $total)
{
    global $all_combos, $locations, $num_all_locations, $longest;

    for ($i = count($to_visit) - 1; $i >= 0; --$i) {
        $new_to_visit = $to_visit;
        $curr = array_splice($new_to_visit, $i, 1)[0];  // Take one out of the to-visit basket, and put in visted basket.

        $new_total = $total;
        if (count($visited) > 0) {
            $last_visited = $visited[count($visited) - 1];
            $new_total += intval($locations[$last_visited][$curr]);
        }

        if (count($to_visit) > 0) {
            $new_visited = $visited;
            $new_visited[] = $curr;
            getNext($new_visited, $new_to_visit, $new_total);
        }  // If there are more in the to-visit basket, keep going.

        if (count($new_visited) === $num_all_locations) {  // If we visited all the locations...
            $all_combos[] = ['path' => $new_visited, 'dist' => $new_total];
            if (0 === $longest['dist'] || $longest['dist'] < $new_total) {
                $longest = ['path' => $new_visited, 'dist' => $new_total];
            }
        }
    }
}
getNext([], $all_locations, 0);

echo 'ALL: '.count($all_combos).PHP_EOL;
echo 'LONGEST:';
print_r($longest);
