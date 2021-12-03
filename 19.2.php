<?php

$allStringLines = [
    "H => HO",
    "H => OH",
    "O => HH",
    "",
    "HOH",
];
// $allStringLines = [
//     "H => HO",
//     "H => OH",
//     "O => HH",
//     "",
//     "HOHOHO",
// ];
// $allStringLines = explode("\n", file_get_contents('19.in.txt'));

$starting = $allStringLines[count($allStringLines) - 1];
$replace = $allStringLines;
array_splice($replace, count($replace) - 2);
// print_r($replace);

// usort($replace, function ($one, $two) {
//     $tokens_one = explode(' => ', $one);
//     $tokens_two = explode(' => ', $two);

//     return strlen($tokens_two[1]) <=> strlen($tokens_one[1]);
// });
// // print_r($replace);

// $num_cycles = 0;
// $new_molecule = $starting;

// while (strlen($new_molecule) > 1) {  // While we've not yet reached 'e'...
//     foreach ($replace as $line) {  // For each replacement string...
//         $tokens = explode(' => ', $line);

//         $found_pos = strpos($new_molecule, $tokens[1]);  // Get first match.

//         while ($found_pos !== false) {
//             // $new_molecule = str_replace(intval($tokens[0]), intval($tokens[1]), $starting);
//             $new_molecule = substr($new_molecule, 0, $found_pos) . $tokens[0] . substr($new_molecule, $found_pos + strlen($tokens[1]));
//             echo "NEW: ${new_molecule}".PHP_EOL;
//             // if (!in_array($new_molecule, $molecules)) {
//             //     $molecules[] = $new_molecule;
//             // }
//             $num_cycles += 1;

//             $new_offset = $found_pos + 1;
//             if ($new_offset > strlen($new_molecule) - 1) {
//                 $found_pos = false;
//             } else {
//                 $found_pos = strpos($new_molecule, $tokens[0], $new_offset);
//             }  // Get next match.
//         }
//     }
// }

echo "NUM: ${num_cycles}".PHP_EOL;
