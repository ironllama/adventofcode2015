<?php
// Manually testing values to check logic.
//$allStringLines = ["ugknbfddgicrmopn", "aaa", "jchzalrnumimnmhp", "haegwjzuvuyypxyu", "dvszwmarrgswjxmb"];

// When creating a file on Windows, the line ending is technically '\r\n', not just '\n'. Leaving the '\r' in
// the string makes for some WEIRD echo outputs. Anyway, the PHP_EOL figures out whether you're on Windows or
// not and uses the correct line ending to use.
$allStringLines = explode(PHP_EOL, file_get_contents("5.1.in.txt"));
//var_dump($allStringLines);

function hasThreeVowels($inString) {
    $allVowels = ["a", "e", "i", "o", "u"];
    $numVowels = 0;
    for ($i = 0; $i < strlen($inString); $i++) {
        if (in_array(substr($inString, $i, 1), $allVowels)){
            $numVowels++;
            if ($numVowels >= 3) return true;
        }
    }
    echo("NAUGHTY >>> Not enough vowels!");
    return false;
}

function hasRepeatingLetter($inString) {
    for ($i = 0; $i < strlen($inString) - 1; $i++) {
        if ($inString[$i] === $inString[$i+1]) return true;
    }
    echo("NAUGHTY >>> No repeating letter!");
    return false;
}

function hasProhibitedCombos($inString) {
    $prohibitedCombos = ["ab", "cd", "pq", "xy"];
    for ($i = 0; $i < count($prohibitedCombos); $i++) {
        if (strpos($inString, $prohibitedCombos[$i]) !== false) {
            echo("NAUGHTY >>> Has prohibited combo [" . $prohibitedCombos[$i] . "]");
            return true;
        }
    }
    return false;
}

// Now just iterate through the inputs and run the above checks.
$goodStrings = 0;
for ($i = 0; $i < count($allStringLines); $i++){
    echo("Testing: " . $allStringLines[$i] . "... ");
    if ( hasThreeVowels($allStringLines[$i])
        && hasRepeatingLetter($allStringLines[$i])
        && !hasProhibitedCombos($allStringLines[$i]) ) {
            $goodStrings++;
            echo("GOOD!");
        }
    echo("\n");
}

echo("Total good lines: " . $goodStrings);
