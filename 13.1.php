<?php

// $allStringLines = [
//     "Alice would gain 54 happiness units by sitting next to Bob.",
//     "Alice would lose 79 happiness units by sitting next to Carol.",
//     "Alice would lose 2 happiness units by sitting next to David.",
//     "Bob would gain 83 happiness units by sitting next to Alice.",
//     "Bob would lose 7 happiness units by sitting next to Carol.",
//     "Bob would lose 63 happiness units by sitting next to David.",
//     "Carol would lose 62 happiness units by sitting next to Alice.",
//     "Carol would gain 60 happiness units by sitting next to Bob.",
//     "Carol would gain 55 happiness units by sitting next to David.",
//     "David would gain 46 happiness units by sitting next to Alice.",
//     "David would lose 7 happiness units by sitting next to Bob.",
//     "David would gain 41 happiness units by sitting next to Carol.",
// ];
$allStringLines = explode("\n", file_get_contents('13.in.txt'));

$locations = [];
foreach ($allStringLines as $line) {
    $line = trim($line, '.');
    $line_tokens = explode(' ', $line);

    $gain = intval($line_tokens[3]);
    if ($line_tokens[2] === 'lose') {
        $gain *= -1;
    }

    if (!array_key_exists($line_tokens[0], $locations)) {
        $locations[$line_tokens[0]] = [$line_tokens[10] => $gain];
    } else {
        $locations[$line_tokens[0]][$line_tokens[10]] = $gain;
    }
}
// print_r($locations);

$all_peeps = array_keys($locations);
$num_all_peeps = count($all_peeps);
$all_combos = [];
$goal_score = ['path' => '', 'score' => 0];

function getNext($visited, $to_visit, $total)
{
    global $all_combos, $locations, $num_all_peeps, $goal_score;

    for ($i = count($to_visit) - 1; $i >= 0; --$i) {  // Loop through each possible next value.
        $new_to_visit = $to_visit;  // Copy array to keep same between iterations.
        $curr = array_splice($new_to_visit, $i, 1)[0];  // Take one out of the to-visit basket.

        $new_total = $total;
        if (count($visited) > 0) {  // If there's stuff already in the list, before we add the curr one.
            $last_visited = $visited[count($visited) - 1];
            $new_total += $locations[$last_visited][$curr] + $locations[$curr][$last_visited];  // Get the score.
        }

        $new_visited = $visited;  // Make a new copy of the array before recursing, to keep the for loop pure.
        $new_visited[] = $curr;  // Add to the new copy of the array.

        if (count($new_to_visit) > 0) {  // If there's more to check out...
            getNext($new_visited, $new_to_visit, $new_total);
        }  // If there are more in the to-visit basket, keep going.
        else {
            $new_total += $locations[$curr][$visited[0]] + $locations[$visited[0]][$curr];  // Last one is now determined!
        }

        if (count($new_visited) === $num_all_peeps) {  // If we visited all the locations...
            $all_combos[] = ['path' => $new_visited, 'score' => $new_total];
            if (0 === $goal_score['score'] || $goal_score['score'] < $new_total) {
                $goal_score = ['path' => $new_visited, 'score' => $new_total];
            }
        }
    }
}
getNext([], $all_peeps, 0);

// print_r($all_combos);
print_r($goal_score);
