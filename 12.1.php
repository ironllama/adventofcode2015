<?php

// $inputString = '[1,2,3]';
// $inputString = '{"a":2,"b":4}';
// $inputString = '[[[3]]]';
// $inputString = '{"a":{"b":4},"c":-1}';
// $inputString = '{"a":[-1,1]}';
// $inputString = '[-1,{"a":1}]';
// $inputString = '[]';
// $inputString = '{}';

$inputString = file_get_contents('12.in.txt');

$input_obj = json_decode($inputString);


// https://stackoverflow.com/questions/173400/how-to-check-if-php-array-is-associative-or-sequential
// function is_assoc($arr) {
//     if (array() === $arr) return false;
//     return array_keys($arr) !== range(0, count($arr) - 1);
// }
$total = 0;
function process_elem($in_elem)
{
    global $total;

    // echo "PROCESSING: ";
    // print_r($in_elem);
    // echo PHP_EOL;

    $type = gettype($in_elem);

    if ($type === 'string') {
        if (is_numeric($in_elem)) {
            $in_elem = floatval($in_elem);
            $type = 'float';
        }
    }

    if ($type === 'integer' || $type === 'float') {
        $total += $in_elem;
    } elseif ($type === 'array') {
        foreach ($in_elem as $elem) {
            process_elem($elem);
        }
    } elseif ($type === 'object') {
        // echo "OBJECT:";
        // print_r($in_elem);
        foreach ($in_elem as $key => $val) {
            process_elem($val);
        }
    }
}
process_elem($input_obj);
echo "TOTAL: ${total}".PHP_EOL;
