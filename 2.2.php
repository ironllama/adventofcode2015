<?php

    $input = file_get_contents("2.2.in");

    //$input = "1x1x10\n2x3x4";
    $runningTotal = 0;

    $allLinesArr = split("\n", $input);
    for($j = 0; $j < count($allLinesArr); $j++) {
        $dimArr = explode("x", trim($allLinesArr[$j]));

        $volume = $dimArr[0] * $dimArr[1] * $dimArr[2];
        echo("[{$j}] VOL: [" . $dimArr[0] . "x" . $dimArr[1] . "x" . $dimArr[2] . "=" . $volume . "]    ");
        $runningTotal += $volume;

        $longestDim = 0;
        $longestIdx = 0;
        for ($i = 0; $i < count($dimArr); $i++) {
            if ($dimArr[$i] > $longestDim) {
            $longestDim = $dimArr[$i];
            $longestIdx = $i;
            }
        }
        //echo $longestIdx;
        $xtraBow = 0;
        $xtraBowDebug = [];
        for ($i = 0; $i < count($dimArr); $i++) {
            if ($i !== $longestIdx)
            {
                $xtraBow += $dimArr[$i];
                $xtraBowDebug[] = $dimArr[$i];
            }
        }
        $xtraBow *= 2;
        echo("XTR: [" . implode("+", $xtraBowDebug) . "*2=" . $xtraBow . "]    ");

        $runningTotal += $xtraBow;
        echo("TOT: [{$runningTotal}]\n");
    }

    echo("RESULT: " . $runningTotal);
?>