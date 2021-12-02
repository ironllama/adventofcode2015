<?php

$inputString = file_get_contents('12.in.txt');
$input_obj = json_decode($inputString);

$total = 0;
function process_elem($in_elem)
{
    global $total;

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
        // Test to see if there is a property called 'red'.
        foreach ((array)$in_elem as $key => $val) {
            // echo "KEY: ${key} VAL: ";
            // var_dump($val);
            // echo PHP_EOL;
            if ($val === 'red') {
                return;
            }
        }

        foreach ($in_elem as $key => $val) {
            process_elem($val);
        }
    }
}
process_elem($input_obj);
echo "TOTAL: ${total}".PHP_EOL;
