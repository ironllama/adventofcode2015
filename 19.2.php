<?php

// $allStringLines = [
//     'e => H',
//     'e => O',
//     'H => HO',
//     'H => OH',
//     'O => HH',
//     '',
//     'HOH',
// ];
// $allStringLines = [
//     'e => H',
//     'e => O',
//     'H => HO',
//     'H => OH',
//     'O => HH',
//     '',
//     'HOHOHO',
// ];
$allStringLines = explode("\n", file_get_contents('19.in.txt'));

$goal = $allStringLines[count($allStringLines) - 1];
// $goal = 'NThSiRnBFArPBSiRnBFArCaRnPBFAr';  // First time puzzle input turns to H or O -- I think we want to wait until the last minute for this?
$replace = $allStringLines;
array_splice($replace, count($replace) - 2);

$new_replace = array_map(fn ($line) => explode(' => ', trim($line)), $replace);  // Probably easier when broken out into array elements?
$new_replace = array_filter($new_replace, fn ($one) => 'H' !== $one[0] && 'O' !== $one[0]);  // Remove single H and O, since they seem irrelevant?
// echo 'REPLACE: ';
// print_r($new_replace);
// echo "\n";

// ============================================================================
// Brute forcing the answer. DOES NOT WORK. Takes too much time/space.
// function process($starting)
// {
//     global $new_replace;

//     $molecules = [];
//     foreach ($new_replace as $tokens) {
//         $found_pos = strpos($starting, $tokens[0]);  // Get first match.
//         while (false !== $found_pos) {
//             $new_molecule = substr($starting, 0, $found_pos).$tokens[1].substr($starting, $found_pos + strlen($tokens[0]));
//             if (!in_array($new_molecule, $molecules)) {
//                 $molecules[] = $new_molecule;
//             }
//             $found_pos = strpos($starting, $tokens[0], $found_pos + 1);  // Get next match.
//         }
//     }

//     return $molecules;
// }

// $found = false;
// $results = process('e');
// $count = 1;
// while (!$found) {
//     ++$count;
//     echo "C: ${count}".PHP_EOL;
//     print_r($results);

//     $all_results = [];
//     foreach ($results as $this_result) {
//         if (strlen($this_result) > strlen($goal)) {  // Skip further processing of molecules that got too big.
//             echo 'SKIPPING -- TOO LONG!'.PHP_EOL;
//             continue;
//         }

//         $new_results = process($this_result);
//         // print_r($new_results);
//         if (in_array($goal, $new_results)) {
//             $found = true;
//             echo 'FOUND!'.PHP_EOL;
//             break;
//         }
//         $all_results = array_merge($all_results, $new_results);
//     }

//     $results = $all_results;
//     // echo 'R: '.count($all_results).PHP_EOL;
//     if (0 === count($all_results)) {
//         echo 'NOT FOUND!'.PHP_EOL;
//         break;
//     }
// }
// echo "CYCLES: ${count}".PHP_EOL;

// ============================================================================
// Trying to find the answer by going backwards -- starting with the long string and reverse substituting back to constituents.
// DOES NOT WORK. Creates molecule that can not be further broken down, without reaching 'e'.
usort($new_replace, function ($one, $two) {
    // $tokens_one_arr = explode(' => ', $one);
    // $tokens_two_arr = explode(' => ', $two);

    $tokens_one = $one[1];
    $tokens_two = $two[1];

    // // Only cap letters.  -- Doesn't seem to matter.
    // $tokens_one = preg_replace('![^A-Z]+!', '', $tokens_one);
    // $tokens_two = preg_replace('![^A-Z]+!', '', $tokens_two);

    // Removing the influence of H and O in the right side.
    // if (strlen($tokens_one) === strlen($tokens_two)) {
    //     $force_lower = ['H', 'O'];
    //     $tokens_one = str_replace($force_lower, '', $tokens_one);
    //     $tokens_two = str_replace($force_lower, '', $tokens_two);

    // Removing the influence of H and O on the left side -- THIS WAS KEY! Probably better to remove from array before sort.
    //     if (strlen($tokens_one) === strlen($tokens_two)) {
    //         if (in_array($tokens_one_arr[0], $force_lower)) {
    //             return 1;
    //         }
    //         if (in_array($tokens_two_arr[0], $force_lower)) {
    //             return -1;
    //         }
    //     }
    // }

    return strlen($tokens_two) <=> strlen($tokens_one);
});
// print_r($new_replace);
echo 'REPLACEMENTS:'.PHP_EOL;
foreach ($new_replace as $line) {
    echo $line[0].' => '.$line[1].PHP_EOL;
}
echo PHP_EOL;

$num_cycles = 0;
$new_molecule = $goal;

// while (strlen($new_molecule) > 1) {  // While we've not yet reached 'e'...
while ('e' != $new_molecule) {  // While we've not yet reached 'e'...
    foreach ($new_replace as $tokens) {  // For each replacement string...
        // $tokens = explode(' => ', trim($line));

        $found_pos = strpos($new_molecule, $tokens[1]);  // Get first match.
        // $found_pos = strpos($new_molecule, $tokens[1]);  // Get last match. Makes a difference if there are H, O's, but still doesn't make it.
        // echo "POS: {$found_pos} TOK: ".$tokens[1].' MOLE: '.$new_molecule.PHP_EOL;
        // echo "POS: {$found_pos} TOK: ".$tokens[1].PHP_EOL;

        // while (false !== $found_pos) {  // Removed. Probably better to start over after every find -- be as greedy as possible.
        if (false !== $found_pos) {
            // $new_molecule = str_replace(intval($tokens[0]), intval($tokens[1]), $starting);
            $new_molecule = substr($new_molecule, 0, $found_pos).$tokens[0].substr($new_molecule, $found_pos + strlen($tokens[1]));
            echo "[{$num_cycles}] R: ".$tokens[1].'->'.$tokens[0]." NEW: ${new_molecule}".PHP_EOL;
            // if (!in_array($new_molecule, $molecules)) {
            //     $molecules[] = $new_molecule;
            // }
            ++$num_cycles;

            // $new_offset = $found_pos + 1;
            // if ($new_offset > strlen($new_molecule) - 1) {
            //     $found_pos = false;
            // } else {
            //     $found_pos = strpos($new_molecule, $tokens[1], $new_offset);
            //     // $found_pos = strrpos($new_molecule, $tokens[1], $new_offset);
            // }  // Get next match.
            // $found_pos = strpos($new_molecule, $tokens[1]);
            // $found_pos = strrpos($new_molecule, $tokens[1]);
            break;
        }
    }
}

echo "NUM: ${num_cycles}".PHP_EOL;
