<?php
// Manually testing values to check logic.
//$allStringLines = ["ugknbfddgicrmopn", "aaa", "jchzalrnumimnmhp", "haegwjzuvuyypxyu", "dvszwmarrgswjxmb"];
//$allStringLines = ["qjhvhtzxzqqjkmpb", "xxyxx", "uurcxstgmygtbstg", "ieodomkazucvgmuy"];

// When creating a file on Windows, the line ending is technically '\r\n', not just '\n'. Leaving the '\r' in
// the string makes for some WEIRD echo outputs. Anyway, the PHP_EOL figures out whether you're on Windows or
// not and uses the correct line ending to use.
$allStringLines = explode(PHP_EOL, file_get_contents("5.1.in.txt"));
//var_dump($allStringLines);

function hasRepeatingTwoLetters($inString) {
    for ($i = 0; $i < strlen($inString) - 3; $i++) {
        $thisCombo = substr($inString, $i, 2);
        for ($j = $i + 2; $j < strlen($inString) - 1; $j++) {
            //echo("Testing: " . $thisCombo . " and ". substr($inString, $j, 2). "\n");
            if ($thisCombo === substr($inString, $j, 2)) return true;
        }
    }
    echo("NAUGHTY >>> No repeating two letters!");
    return false;
}

function hasLetterStraddling($inString) {
    for ($i = 0; $i < strlen($inString) - 1; $i++) {
        if (substr($inString, $i, 1) === substr($inString, $i+2, 1)) return true;
    }
    echo("NAUGHTY >>> No letter straddling!");
    return false;
}

// Now just iterate through the inputs and run the above checks.
$goodStrings = 0;
for ($i = 0; $i < count($allStringLines); $i++){
    echo("Testing: " . $allStringLines[$i] . "... ");
    if ( hasRepeatingTwoLetters($allStringLines[$i])
        && hasLetterStraddling($allStringLines[$i]) ) {
            $goodStrings++;
            echo("GOOD!");
        }
    echo("\n");
}

echo("Total good lines: " . $goodStrings);
