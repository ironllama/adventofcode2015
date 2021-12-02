<?php

// $allStringLines = [
//     '20',
//     '15',
//     '10',
//     '5',
//     '5',
// ];
// $storage_goal = 25;

$allStringLines = explode("\n", file_get_contents('17.in.txt'));
$storage_goal = 150;

$allIntLines = array_map(fn ($line) => intval($line), $allStringLines);
// print_r($allIntLines);

$storage_goal_combo = [];

function get_another($visited, $score)
{
    global $allIntLines, $storage_goal, $storage_goal_combo;

    if (count($visited) > 0) {
        $last_visited = $visited[count($visited) - 1];
    } else {
        $last_visited = 0;
    }

    for ($i = $last_visited; $i < count($allIntLines); ++$i) {
        if (in_array($i, $visited)) {
            continue;
        }
        $new_visited = $visited;
        $new_visited[] = $i;

        $new_score = $score + $allIntLines[$i];
        // echo "SCORE: ${new_score} PATH: ".implode(', ', $new_visited).PHP_EOL;

        if ($new_score < $storage_goal) {
            get_another($new_visited, $new_score);
        } elseif ($new_score === $storage_goal) {
            $storage_goal_combo[] = $new_visited;
        }
    }
}
get_another([], 0);

// print_r($storage_goal_combo);
$min_size = 0;
$min_combos = [];
foreach ($storage_goal_combo as $this_combo) {
    $this_size = count($this_combo);
    if (0 === $min_size || $this_size < $min_size) {
        $min_size = $this_size;
        $min_combos = [$this_combo];
    } elseif ($this_size === $min_size) {
        $min_combos[] = $this_combo;
    }
}

foreach ($min_combos as $this_combo) {
    $translated = array_map(fn ($input) => $allIntLines[$input], $this_combo);
    echo implode(' -> ', $translated).PHP_EOL;
}

echo "SIZE: ${min_size}".PHP_EOL;
echo 'NUM: '.count($min_combos).PHP_EOL;
