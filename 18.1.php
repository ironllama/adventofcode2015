<?php

// $allStringLines = [
//     '.#.#.#',
//     '...##.',
//     '#....#',
//     '..#...',
//     '#.#..#',
//     '####..',
// ];
$allStringLines = explode("\n", file_get_contents('18.in.txt'));

function next_stage($last_state)
{
    $next_state = $last_state;

    for ($i = 0; $i < count($next_state); ++$i) {  // For each row...
        $line = $last_state[$i];
        $line_arr = str_split($line);

        for ($k = 0; $k < count($line_arr); ++$k) {  // For each char...
            // Adjust for edges.
            $start_row = $i - 1;
            if (0 === $i) {
                $start_row = 0;
            }
            $end_row = $i + 1;
            if ($end_row > count($next_state) - 1) {
                $end_row = $i;
            }
            // echo "ROW: ${i} ${start_row} ${end_row}".PHP_EOL;

            $start_col = $k - 1;
            if (0 === $k) {
                $start_col = 0;
            }
            $end_col = $k + 1;
            if ($end_col > count($line_arr) - 1) {
                $end_col = $k;
            }
            // echo "COL: ${k} ${start_col} ${end_col}".PHP_EOL;

            // Check neighbors.
            $num_lights_on = 0;
            for ($r = $start_row; $r <= $end_row; ++$r) {
                for ($c = $start_col; $c <= $end_col; ++$c) {
                    // echo "[${i}, ${k}] CHECKING: [${r}, ${c}]".PHP_EOL;
                    if ($i === $r && $k === $c) {
                        continue;
                    }
                    if ('#' === substr($last_state[$r], $c, 1)) {
                        ++$num_lights_on;
                    }
                }
            }

            // Adjust next stage.
            if ('#' === $line_arr[$k] && 2 !== $num_lights_on && 3 !== $num_lights_on) {
                $new_line = str_split($next_state[$i]);
                $new_line[$k] = '.';
                $next_state[$i] = implode('', $new_line);
            } elseif ('.' === $line_arr[$k] && 3 === $num_lights_on) {
                $new_line = str_split($next_state[$i]);
                $new_line[$k] = '#';
                $next_state[$i] = implode('', $new_line);
            }
        }
    }

    return $next_state;
}

$new_stage = $allStringLines;
// foreach (range(1, 4) as $i) {
foreach (range(1, 100) as $i) {
    $new_stage = next_stage($new_stage);
}

$total_on = 0;
foreach ($new_stage as $line) {
    foreach (str_split($line) as $point) {
        if ('#' === $point) {
            ++$total_on;
        }
    }
}
print_r($new_stage);
echo "TOTAL ON: ${total_on}".PHP_EOL;
