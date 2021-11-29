<?php

function process_invalids(&$input_array, $invalid)
{
    $invalid_pos = array_search($invalid, $input_array);
    if (false !== $invalid_pos) {
        $input_array[$invalid_pos] = chr(ord($invalid) + 1);
        for ($i = $invalid_pos + 1; $i < count($input_array); ++$i) {
            $input_array[$i] = 'a';
        }

        return true;
    }

    return false;
}

function next_value($input_array)
{
    // global $valid;

    if (!process_invalids($input_array, 'i')
        && !process_invalids($input_array, 'l')
        && !process_invalids($input_array, 'o')) {
        for ($i = count($input_array) - 1; $i >= 0; --$i) {
            $curr = $input_array[$i];

            if ('z' !== $curr) {
                $curr_num = ord($curr);
                $new_num = $curr_num + 1;
                $input_array[$i] = chr($new_num);
                break;
            } else {
                $input_array[$i] = 'a';
            }
        }
    }

    return $input_array;
}

function test_straight_three($input_array)
{
    for ($i = 0; $i < count($input_array) - 2; ++$i) {
        if (ord($input_array[$i]) + 1 === ord($input_array[$i + 1])) {
            if (ord($input_array[$i]) + 2 === ord($input_array[$i + 2])) {
                return true;
            }
        }
    }

    return false;
}

function test_two_pairs($input_array)
{
    $dups = [];
    for ($i = 0; $i < count($input_array) - 1; ++$i) {
        if ($input_array[$i] === $input_array[$i + 1]) {
            if (!in_array($input_array[$i], $dups)) {
                $dups[] = $input_array[$i];
            }
            ++$i;  // Skip the dup - can't overlap pairs.
        }
    }

    if (2 === count($dups)) {
        return true;
    } else {
        return false;
    }
}

function next_valid($input_array)
{
    for (;;) {
        $next = next_value($input_array);
        if (test_straight_three($next) && test_two_pairs($next)) {
            return $next;
        }
        $input_array = $next;
    }
}

// echo implode('', next_valid(str_split('vzbxkghb'))).PHP_EOL;  // vzbxxyzz
echo implode('', next_valid(str_split('vzbxxyzz'))).PHP_EOL;  // vzcaabcc
