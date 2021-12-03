<?php

// $allStringLines = [
//     "H => HO",
//     "H => OH",
//     "O => HH",
//     "",
//     "HOH",
// ];
// $allStringLines = [
//     "H => HO",
//     "H => OH",
//     "O => HH",
//     "",
//     "HOHOHO",
// ];
$allStringLines = explode("\n", file_get_contents('19.in.txt'));

$starting = $allStringLines[count($allStringLines) - 1];
$replace = $allStringLines;
array_splice($replace, count($replace) - 2);
// print_r($replace);

$molecules = [];
foreach ($replace as $line) {
    $tokens = explode(' => ', $line);

    $found_pos = strpos($starting, $tokens[0]);  // Get first match.

    while ($found_pos !== false) {
        // $new_molecule = str_replace(intval($tokens[0]), intval($tokens[1]), $starting);
        $new_molecule = substr($starting, 0, $found_pos) . $tokens[1] . substr($starting, $found_pos + strlen($tokens[0]));
        // echo "NEW: ${new_molecule}".PHP_EOL;
        if (!in_array($new_molecule, $molecules)) {
            $molecules[] = $new_molecule;
        }

        $found_pos = strpos($starting, $tokens[0], $found_pos + 1);  // Get next match.
    }
}

// print_r($molecules);
echo "NUM: " . count($molecules) . PHP_EOL;
