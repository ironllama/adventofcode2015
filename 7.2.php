<?php

$allStringLines = explode("\n", file_get_contents("7.1.in.txt"));

// Reorganize data by target.
$allWires = [];
for ($i = 0; $i < count($allStringLines); $i++) {
    $assOper = explode(' -> ', $allStringLines[$i]);
    if (count($assOper) === 1) continue;  // Skip blank lines.
    $allWires[trim($assOper[1])] = $assOper[0];
}
$allWires['b'] = 3176;  // THE ONLY THING CHANGED FROM 7.1

$allSignals = [];
function evaluate_node($target) {
    global $allWires, $allSignals;

    if (! $allSignals[$target]) {  // If not already set...
        if (is_numeric($target)) $allSignals[$target] = (int)$target;
        else {
            $operations = $allWires[$target];
            if ($operations) {
                $logicOper = explode(" ", trim($operations));

                if (count($logicOper) === 1) {
                    if (is_numeric($logicOper[0])) {
                        $allSignals[$target] = (int)$logicOper[0];
                    }
                    else {
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

echo 'A:' . evaluate_node('a') . PHP_EOL;