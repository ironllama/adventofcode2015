<?php

// $valid = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

// echo count($valid);

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
                // $curr_num = array_search($curr, $valid);
                $curr_num = ord($curr);
                $new_num = $curr_num + 1;
                // $input_array[$i] = $valid[$new_num];
                $input_array[$i] = chr($new_num);
                break;
            } else {
                $input_array[$i] = 'a';
            }
        }
    }

    return $input_array;
}

// echo implode('', next_value(str_split('abcdefzz'))).PHP_EOL;  // abcdgaa
// echo implode('', next_value(str_split('abidefzz'))).PHP_EOL;  // abjaaaa

function test_straight_three($input_array)
{
    // global $valid;

    // $diffs = [];  // Array containing differences in letter position between current and next element.
    for ($i = 0; $i < count($input_array) - 2; ++$i) {
        // $diffs[] = ''.abs(array_search($input_array[$i], $valid) - array_search($input_array[$i + 1], $valid));
        // $diffs[] = ''.abs(ord($input_array[$i]) - ord($input_array[$i + 1]));
        // echo 'TEST 3: '.$input_array[$i].' '.$input_array[$i + 2].PHP_EOL;
        if (ord($input_array[$i]) + 1 === ord($input_array[$i + 1])) {
            if (ord($input_array[$i]) + 2 === ord($input_array[$i + 2])) {
                return true;
            }
        }
    }

    return false;
    // $diffs_str = implode('', $diffs);

    // echo "DIFF_STR: ${diffs_str} FOUND: ".strpos($diffs_str, '11').PHP_EOL;
    // if (false !== strpos($diffs_str, '11')) {  // Look for 3 in sequence.
    //     return true;
    // } else {
    //     return false;
    // }
}

// echo test_straight_three(str_split('abcdffaa'));
// echo test_straight_three(str_split('abceffaa'));
// echo (test_straight_three(str_split('ghjaabcc')) ? 'PASS' : 'FAIL').PHP_EOL;

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

// echo (test_two_pairs(str_split('abcdffaa')) ? 'PASS' : 'FAIL').PHP_EOL;
// echo (test_two_pairs(str_split('ghjaabcc')) ? 'PASS' : 'FAIL').PHP_EOL;
// echo (test_straight_three(str_split('abceffab')) ? 'PASS' : 'FAIL').PHP_EOL;

function next_valid($input_array)
{
    for (;;) {
        $next = next_value($input_array);
        // echo 'TESTING NEXT: '.implode('', $next).' 2: '.(test_two_pairs($next) ? 'PASS' : 'FAIL').' 3: '.(test_straight_three($next) ? 'PASS' : 'FAIL').PHP_EOL;
        if (test_straight_three($next) && test_two_pairs($next)) {
            return $next;
        }
        $input_array = $next;
    }
}

// echo implode('', next_valid(str_split('abcdefgh'))).PHP_EOL;  // abcdffaa
// echo implode('', next_valid(str_split('ghijklmn'))).PHP_EOL;  // ghjaabcc

echo implode('', next_valid(str_split('vzbxkghb'))).PHP_EOL;  // ghjaabcc
