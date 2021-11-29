<?php

$input = '1113122113';
$loops = 50;  // ONLY CHANGED THIS LINE.

function process($input)
{
    $input_tokens = str_split($input);
    $output = '';

    for ($j = 0; $j < count($input_tokens); ++$j) {
        $curr_token = $input_tokens[$j];

        // Count repeats, if any.
        $num = 1;
        for ($k = $j + 1; $k < count($input_tokens); ++$k) {
            $next_token = $input_tokens[$k];
            if ($next_token !== $curr_token) {
                break;
            } else {
                ++$num;
            }
        }

        $j += ($num - 1);
        $output .= $num.$curr_token;
    }

    return $output;
}

for ($i = 0; $i < $loops; ++$i) {
    $input = process($input);
    // echo "LOOP[${i}]: {$input}".PHP_EOL;
}

// echo "FINISHED: ${input}".PHP_EOL;
echo 'LENGTH: '.strlen($input).PHP_EOL;
