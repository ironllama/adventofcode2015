<?php

//$allStringLines = ["123 -> x", "456 -> y", "x AND y -> d", "x OR y -> e", "x LSHIFT 2 -> f", "y RSHIFT 2 -> g", "NOT x -> h", "NOT y -> i"];
$allStringLines = explode("\n", file_get_contents("7.1.in.txt"));

$allVars = array();
for ($i = 0; $i < count($allStringLines); $i++) {
    $assOper = explode("->", $allStringLines[$i]);
    if (count($assOper) === 1) continue;
    $thisVar = trim($assOper[1]);

    $logicOper = explode(" ", trim($assOper[0]));
    if (count($logicOper) === 1 && is_numeric($logicOper[0])) $allVars[$thisVar] = (int)$logicOper[0];
    else if (count($logicOper) === 2 && $logicOper[0] === "NOT") {
        $allVars[$thisVar] = (~ (int)$allVars[$logicOper[1]]) & 65535; // Flip (~) and resign/overflow correct ($)
    }
    else {
        if ($logicOper[1] === "AND") $allVars[$thisVar] = (int)$allVars[$logicOper[0]] & (int)$allVars[$logicOper[2]];
        else if ($logicOper[1] === "OR") $allVars[$thisVar] = (int)$allVars[$logicOper[0]] | (int)$allVars[$logicOper[2]];
        else if ($logicOper[1] === "LSHIFT") $allVars[$thisVar] = (int)$allVars[$logicOper[0]] << (int)$logicOper[2];
        else if ($logicOper[1] === "RSHIFT") $allVars[$thisVar] = (int)$allVars[$logicOper[0]] >> (int)$logicOper[2];
    }
}

ksort($allVars);

// for ($i = 0; $i < count($allVars); $i++) { }
var_dump($allVars);
