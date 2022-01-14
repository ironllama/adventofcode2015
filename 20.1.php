<?php

// $limit = 130;
$limit = 29000000;

$i = 0;
$total_presents = 0;
while ($total_presents < $limit) {
    // if ((0 == $i % 2)
    //     && (0 == $i % 3)
    //     && (0 == $i % 5)
    //     && (0 == $i % 7)
    //     && (0 == $i % 11)
    //     // && (0 == $i % 13)
    //     ) {  // Only include those that are divisible by a smaller prime.
    $total_presents = 0;
    for ($elf = 1; $elf <= $i; ++$elf) {
        if (0 == $i % $elf) {
            $total_presents += $elf * 10;
        }
    }
    echo "House {$i} got {$total_presents}.\n";
    // }
    // ++$i;
    $i += (2 * 3 * 5 * 7 * 11);
}

// // Adapted from (https://anupamsaha.wordpress.com/2011/05/16/find-total-number-of-divisors-of-an-integer-in-php/)
// // count number of divisors of a number

// function countDivisors($num)
// {
//     if (1 == $num) {
//         return 1;
//     }

//     $factors = getPrimeFactors($num);

//     $array_primes = array_count_values($factors);  // Freq count.
//     $divisors = 1;
//     foreach ($array_primes as $power) {
//         $divisors *= ++$power;
//     }

//     return $divisors;
// }

// function getPrimeFactors($num)
// {
//     if (1 == $num) {
//         return [1];
//     }

//     $factors = [];

//     // while (@$factors[0] !== $num) {
//     while (true) {
//         // $root = ceil(sqrt($num)) + 1;
//         $root = sqrt($num);
//         $i = 2;

//         while ($i <= $root) {
//             if (0 == $num % $i) {  // Factor?
//                 $factors[] = $i;
//                 $num = $num / $i;  // Divide number by factor
//                 continue;  // Start over with new number!
//             }
//             ++$i;
//         }

//         // $factors[] = $num;  // Include last number?
//         break;
//     }

//     return $factors;
// }

// // $this_num = 4;
// // $this_num = 100;
// // $this_num = 295048;
// // $this_num = 38473;
// $this_num = 665280;
// echo 'getPrimeFactors: [', implode(', ', getPrimeFactors($this_num)), ']', PHP_EOL;
// echo 'countDivisors: ', $this_num, ' -> ', countDivisors($this_num), PHP_EOL;

// count_to($this_num, 'countDivisors');
// count_to($this_num, 'divCount');

// function count_to($num, $count_func)
// {
//     $time_start = microtime(true);
//     $old = 0;
//     for ($i = 1; $i <= $num; ++$i) {
//         $new = $count_func($i);
//         // echo $i, ': ', $new, PHP_EOL;
//         if ($new > $old) {
//             echo '>>> ', $i, PHP_EOL;
//             $old = $new;
//         }
//     }
//     echo 'Time: '.(microtime(true) - $time_start);
// }

// // Adapted to PHP from (https://www.geeksforgeeks.org/highly-composite-numbers/)
// // Slower than above.
// function divCount($n)
// {
//     // sieve method for prime calculation
//     // $hash = array_fill(0, $n + 1, true);
//     $hash = array_fill(0, $n + 1, 1);

//     for ($p = 2; $p * $p < $n; ++$p) {  // For each p starting from 2, as a power of p (p2, p3, etc.)
//             // if (true == $hash[$p]) {  // Primes not yet determined...
//             if (1 == $hash[$p]) {  // Primes not yet determined...
//                 for ($i = $p * 2; $i < $n; $i += $p) {  // $p * 2 is $p + $p to skip the first number (itself) in sequence.
//                     // $hash[$i] = false;
//                     $hash[$i] = 0;
//                 }
//             }
//     }
//     // echo 'SIEVE:', implode(' ', $hash), PHP_EOL;

//     // Traversing through all prime numbers
//     $total = 1;
//     for ($p = 2; $p <= $n; ++$p) {
//         if ($hash[$p]) {
//             // calculate number of divisor with formula
//             // total div = (p1+1)*(p2+1)* ..... *(pn+1) where n = (a1^p1)*(a2^p2)* .... *(an^pn) ai being prime divisor
//             // for n and pi are their respective power in factorization
//             $count = 0;
//             if (0 == $n % $p) {
//                 while (0 == $n % $p) {
//                     $n = $n / $p;
//                     ++$count;
//                 }
//                 $total *= ++$count;
//             }
//         }
//     }

//     return $total;
// }
// // echo 'divCount: ', $this_num, ' -> ', divCount($this_num), PHP_EOL;
