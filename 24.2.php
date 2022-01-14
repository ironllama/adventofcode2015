<?php

// $allStringLines = [
//     '1',
//     '2',
//     '3',
//     '4',
//     '5',
//     '7',
//     '8',
//     '9',
//     '10',
//     '11',
// ];
$allStringLines = explode("\n", file_get_contents('24.in.txt'));

$allStringNums = array_reduce($allStringLines, function ($acc, $line) {
    $acc[] = intval($line);

    return $acc;
});
$allStringNums = array_reverse($allStringNums);

$num_each_section = array_sum($allStringLines) / 4;  // Changed just this to make FOUR compartments.
$combos = [];

function find_combos($allStringNums, $num_each_section, $start_from, $acc)
{
    global $combos;

    $sum_acc = array_sum($acc);
    for ($i = $start_from; $i < count($allStringNums); ++$i) {
        $this_num = $allStringNums[$i];
        $new_acc = [...$acc, $this_num];
        // echo 'TESTING: ['.implode(', ', $new_acc).'] = '.($sum_acc + $this_num).PHP_EOL;
        if (($sum_acc + $this_num) == $num_each_section) {
            if (0 == count($combos) || count($combos[0]) == count($new_acc)) {  // If new or same size containers, add new one.
                $combos[] = $new_acc;
            } elseif (count($combos[0]) > count($new_acc)) {  // If shorter, start new one with shorter version.
                $combos = [$new_acc];
            }

            return;  // Short-circuit return, if we've found a good path.
        } else {
            if ($sum_acc < $num_each_section) {
                find_combos($allStringNums, $num_each_section, $i + 1, $new_acc);
            }
        }
    }
}

find_combos($allStringNums, $num_each_section, 0, []);
$lowest_entanglement = PHP_INT_MAX;
$lowest_entanglement_group = [];
foreach ($combos as $combo) {
    // echo 'ALL COMBOS:['.implode(', ', $combo), "]\n";
    $quantum_value = array_product($combo);
    if ($quantum_value < $lowest_entanglement) {
        $lowest_entanglement = $quantum_value;
        $lowest_entanglement_group = $combo;
    }
}
echo 'FINISHED: '.$lowest_entanglement.' FROM: ['.implode(', ', $lowest_entanglement_group).']'.PHP_EOL;
