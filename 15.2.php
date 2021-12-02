<?php

// $allStringLines = [
//     'Butterscotch: capacity -1, durability -2, flavor 6, texture 3, calories 8',
//     'Cinnamon: capacity 2, durability 3, flavor -2, texture -1, calories 3',
// ];
$allStringLines = explode("\n", file_get_contents('15.in.txt'));

$ingredients = [];

foreach ($allStringLines as $line) {
    $filtered_line = preg_replace('/[:,]/', '', $line);
    // echo "FILTERED: ${filtered_line}".PHP_EOL;
    $line_tokens = explode(' ', $filtered_line);

    $ingredients[] = [
        'name' => $line_tokens[0],
        'capacity' => intval($line_tokens[2]),
        'durability' => intval($line_tokens[4]),
        'flavor' => intval($line_tokens[6]),
        'texture' => intval($line_tokens[8]),
        'calories' => intval($line_tokens[10]),
    ];
}

// echo 'INGREDIENTS:';
// print_r($ingredients);
// echo PHP_EOL;
$highest_score = 0;
$highest_combo = [];
function next_ingredient($in_num, $stage, $scores)
{
    global $ingredients, $highest_score, $highest_combo;

    if ($stage < count($ingredients) - 1) {
        foreach (range(1, 100 - $stage - $in_num - 1) as $num) {
            $new_scores = $scores;  // So each run through the recusion uses a new copy.
            $new_scores['capacity'] += $ingredients[$stage]['capacity'] * $num;
            $new_scores['durability'] += $ingredients[$stage]['durability'] * $num;
            $new_scores['flavor'] += $ingredients[$stage]['flavor'] * $num;
            $new_scores['texture'] += $ingredients[$stage]['texture'] * $num;
            $new_scores['calories'] += $ingredients[$stage]['calories'] * $num;
            $new_scores[$ingredients[$stage]['name']] = $num;

            next_ingredient($num + $in_num, $stage + 1, $new_scores);
        }
    } else {
        $new_scores = $scores;  // So each run through the recusion uses a new copy.
        $num = 100 - $in_num;
        $new_scores['capacity'] += $ingredients[$stage]['capacity'] * $num;
        $new_scores['durability'] += $ingredients[$stage]['durability'] * $num;
        $new_scores['flavor'] += $ingredients[$stage]['flavor'] * $num;
        $new_scores['texture'] += $ingredients[$stage]['texture'] * $num;
        $new_scores['calories'] += $ingredients[$stage]['calories'] * $num;

        if ($new_scores['capacity'] < 0) {
            $new_scores['capacity'] = 0;
        }
        if ($new_scores['durability'] < 0) {
            $new_scores['durability'] = 0;
        }
        if ($new_scores['flavor'] < 0) {
            $new_scores['flavor'] = 0;
        }
        if ($new_scores['texture'] < 0) {
            $new_scores['texture'] = 0;
        }
        if ($new_scores['calories'] < 0) {
            $new_scores['calories'] = 0;
        }

        $new_scores[$ingredients[$stage]['name']] = $num;

        $this_score = $new_scores['capacity'] * $new_scores['durability'] * $new_scores['flavor'] * $new_scores['texture'];
        if ($this_score > $highest_score && 500 === $new_scores['calories']) {
            $highest_score = $this_score;
            $highest_combo = $new_scores;  // Should be a snapshot copy.
        }
    }
}

$scores = [];
$scores['capacity'] = 0;
$scores['durability'] = 0;
$scores['flavor'] = 0;
$scores['texture'] = 0;
$scores['calories'] = 0;

next_ingredient(0, 0, $scores);

echo "HIGHEST: ${highest_score}".PHP_EOL;
print_r($highest_combo);
