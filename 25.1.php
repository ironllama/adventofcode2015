<?php

    // To continue, please consult the code grid in the manual.  Enter the code at row 2981, column 3075.

    // The first column of row is an iteration number determined by the guassian sum of the previous rows + 1;
    // ((2980 + 1) * 2)

    function gauss_sum($x)
    {
        return intval((abs($x + 1) / 2.0) * $x);
    }

    function get_iter_pos($row, $col)
    {
        $row_one = gauss_sum($col);
        $row_diff = $col + ($row - 2);

        return gauss_sum($row_diff) - gauss_sum($col - 1) + $row_one;
    }

    $code_row = 2981;
    $code_col = 3075;
    $pos_iter = get_iter_pos($code_row, $code_col);
    // echo "POS_ITER: {$pos_iter}\n";

    // $pos_iter = 18;
    $curr_val = strval(20151125);
    for ($i = 2; $i <= $pos_iter; ++$i) {
        $curr_val = bcmod(bcmul($curr_val, '252533'), '33554393');
    }
    echo $curr_val;
