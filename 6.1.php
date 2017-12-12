<?php

//$allStringLines = ["turn on 0,0 through 999,999", "toggle 0,0 through 999,0", "turn off 499,499 through 500,500"];
$allStringLines = explode("\n", file_get_contents("6.1.in.txt"));

// Initialize the array of lights.
$gridArr = array();
for ($i = 0; $i < 1000; $i++) {
    for ($j = 0; $j < 1000; $j++) {
        $gridArr[$i][$j] = false;
    }
}
//echo("SIZE: " . count($allStringLines) . "\n");

// Process the instructions and generate individual lights to change the lights.
for ($i = 0; $i < count($allStringLines); $i++) {
    // Skip empty lines.
    if (trim($allStringLines[$i]) === "") continue;

    $instructions = explode(" ", $allStringLines[$i]);
    if ($instructions[0] === "toggle") {
        $startCoords = explode(",", $instructions[1]);
        $endingCoords = explode(",", $instructions[3]);
        $doWhatStr = $instructions[0];
    }
    else {
        $startCoords = explode(",", $instructions[2]);
        $endingCoords = explode(",", $instructions[4]);
        $doWhatStr = $instructions[1];
    }

    change($startCoords, $endingCoords, $doWhatStr);
}

function change ($startArr, $endArr, $doWhatStr) {
    global $gridArr;
    echo("CHANGE: [" . $startArr[0] . "],[" . $startArr[1] . "] to [" . $endArr[0] . "],[" . $endArr[1] . "] doing [" . $doWhatStr . "]\n");
    for ($i = $startArr[0]; $i <= $endArr[0]; $i++) {
        for ($j = $startArr[1]; $j <= $endArr[1]; $j++) {
            if ($doWhatStr === "on") $gridArr[$i][$j] = true;
            else if ($doWhatStr === "off") $gridArr[$i][$j] = false;
            else $gridArr[$i][$j] = !($gridArr[$i][$j]);
        }
    }
}

$numLightsOn = 0;
for ($i = 0; $i < count($gridArr); $i++) {
    for ($j = 0; $j < count($gridArr[$i]); $j++) {
        if ($gridArr[$i][$j] === true) $numLightsOn++;
    }
}
echo("Num of Lights On: " . $numLightsOn);