<?php

// $allStringLines = [
//     'inc a',
//     'inc a',
//     // 'jio a, +2',
//     'jie a, +2',
//     // 'jmp +2',
//     'tpl a',
//     'inc a',
// ];
$allStringLines = explode("\n", file_get_contents('23.in.txt'));

$registers = ['a' => 1, 'b' => 0];  // Only change!

$i = 0;
while ($i < count($allStringLines)) {
    $line_tokens = explode(' ', trim($allStringLines[$i]));
    $curr_line = $i;  // For debug output.
    switch ($line_tokens[0]) {
        case 'hlf':
            $registers[$line_tokens[1]] /= 2;
            ++$i;
            break;
        case 'tpl':
            $registers[$line_tokens[1]] *= 3;
            ++$i;
            break;
        case 'inc':
            ++$registers[$line_tokens[1]];
            ++$i;
            break;
        case 'jmp':
            $i += intval($line_tokens[1]);
            break;
        case 'jie':
            $reg = $registers[$line_tokens[1][0]];  // Get rid of the comma.
            $val = intval($line_tokens[2]);
            if (0 == $reg % 2) {
                $i += $val;
            } else {
                ++$i;
            }
            break;
        case 'jio':
            $reg = $registers[$line_tokens[1][0]];  // Get rid of the comma.
            $val = intval($line_tokens[2]);
            $reg = $registers[$line_tokens[1][0]];
            // if (1 == $reg % 2) {
            if (1 == $reg) {  // WEIRD.
                $i += $val;
            } else {
                ++$i;
            }
            break;
    }
    // echo 'INSTRUCTIONS: LINE('.$curr_line.') NEXT(' , $i , ') [', implode(', ', $line_tokens), '] REGISTERS: ', implode(', ', $registers), PHP_EOL;
    // print_r($registers);
}

echo 'FINAL REGISTERS:', implode(', ', $registers), PHP_EOL;
