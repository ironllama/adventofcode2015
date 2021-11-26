<?php

// $allStringLines = [
//     "123 -> x",
//     "456 -> y",
//     "x AND y -> d",
//     "x OR y -> e",
//     "x LSHIFT 2 -> f",
//     "y RSHIFT 2 -> g",
//     "NOT x -> h",
//     "NOT y -> i"
// ];

$allStringLines = explode("\n", file_get_contents("7.1.in.txt"));

// $allWires = array();
// for ($i = 0; $i < count($allStringLines); $i++) {
//     $assOper = explode(" -> ", $allStringLines[$i]);
//     if (count($assOper) === 1) continue;  // Skip blank lines.
//     $targetVar = trim($assOper[1]);

//     $logicOper = explode(" ", trim($assOper[0]));
//     // if (count($logicOper) === 1 && is_numeric($logicOper[0])) $allWires[$targetVar] = (int)$logicOper[0];
//     if (count($logicOper) === 1) {
//         if (is_numeric($logicOper[0])) $allWires[$targetVar] = (int)$logicOper[0];
//         else {
//             print_r($allWires);
//             echo "Setting {$targetVar}: index {$logicOper[0]} value {$allWires[$logicOper[0]]}" . PHP_EOL;
//             $allWires[$targetVar] = $allWires[$logicOper[0]];
//         }
//     }
//     else if (count($logicOper) === 2 && $logicOper[0] === "NOT") {
//         $allWires[$targetVar] = (~ (int)$allWires[$logicOper[1]]) & 65535; // Flip (~) and resign/overflow correct ($)
//     }
//     else {
//         if ($logicOper[1] === "AND") $allWires[$targetVar] = (int)$allWires[$logicOper[0]] & (int)$allWires[$logicOper[2]];
//         else if ($logicOper[1] === "OR") $allWires[$targetVar] = (int)$allWires[$logicOper[0]] | (int)$allWires[$logicOper[2]];
//         else if ($logicOper[1] === "LSHIFT") $allWires[$targetVar] = (int)$allWires[$logicOper[0]] << (int)$logicOper[2];
//         else if ($logicOper[1] === "RSHIFT") $allWires[$targetVar] = (int)$allWires[$logicOper[0]] >> (int)$logicOper[2];
//     }
// }
// ksort($allWires);

// Reorganize data by target.
$allWires = [];
for ($i = 0; $i < count($allStringLines); $i++) {
    $assOper = explode(' -> ', $allStringLines[$i]);
    if (count($assOper) === 1) continue;  // Skip blank lines.
    $allWires[trim($assOper[1])] = $assOper[0];
}

$allSignals = [];

function evaluate_node($target) {
    global $allWires, $allSignals;

    if (! $allSignals[$target]) {  // If not already set...
        if (is_numeric($target)) $allSignals[$target] = (int)$target;
        else {
            $operations = $allWires[$target];
            if ($operations) {
                // echo "CHECKING: ${operations}" . PHP_EOL;
                $logicOper = explode(" ", trim($operations));

                if (count($logicOper) === 1) {
                    if (is_numeric($logicOper[0])) {
                        $allSignals[$target] = (int)$logicOper[0];
                    }
                    else {
                        // print_r($allWires);
                        // echo "Setting {$targetVar}: index {$logicOper[0]} value {$allWires[$logicOper[0]]}" . PHP_EOL;
                        $allSignals[$target] = evaluate_node($logicOper[0]);
                    }
                }
                else if (count($logicOper) === 2 && $logicOper[0] === "NOT") {
                    $allSignals[$target] = ((~ evaluate_node($logicOper[1])) & 65535); // Flip (~) and resign/overflow correct ($)
                }
                else {
                    if ($logicOper[1] === "AND") $allSignals[$target] = evaluate_node($logicOper[0]) & evaluate_node($logicOper[2]);
                    else if ($logicOper[1] === "OR") $allSignals[$target] = evaluate_node($logicOper[0]) | evaluate_node($logicOper[2]);
                    else if ($logicOper[1] === "LSHIFT") $allSignals[$target] = evaluate_node($logicOper[0]) << evaluate_node($logicOper[2]);
                    else if ($logicOper[1] === "RSHIFT") $allSignals[$target] = evaluate_node($logicOper[0]) >> evaluate_node($logicOper[2]);
                }

            }
        }
    }
    return $allSignals[$target];
}


// print_r($allWires);
// echo "allWires[a]: ${allWires['a']}" . PHP_EOL;
echo 'A:' . evaluate_node('a') . PHP_EOL;
