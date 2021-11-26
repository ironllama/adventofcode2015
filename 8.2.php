<?php
// $allStringLines = [
//     '""',
//     '"abc"',
//     '"aaa\"aaa"',
//     '"\x27"',
    // '"\xa8br\x8bjr\""',
    // '"nq"',
    // '"zjrfcpbktjmrzgsz\xcaqsc\x03n\"huqab"',
//     '"eitk\\\\f\\\\mhcqqoym\\\\ji"',
// ];
$allStringLines = explode("\n", file_get_contents("8.1.in.txt"));

$total_literals = 0;
$total_values = 0;

foreach ($allStringLines as $line) {
    $chars = str_split(json_encode($line));  // THE ONLY LINE CHANGED FROM 8.1 -- used json_encode to escape another time
    print_r($chars);

    for ($i = 0; $i < count($chars); $i++) {
        $letter = $chars[$i];
        // echo "--> LETTER: ${letter}\n";
        if (($i === 0 || $i === (count($chars) - 1)) && $letter === '"') {
            // echo "START/END\n";
            $total_literals += 1;
            continue;
        }

        if ($letter === '\\') {
            // echo "--> ESC: [" . json_encode($chars[$i + 1]) . "]\n";
            if ($chars[$i + 1] === '"' || $chars[$i + 1] === '\\') {
                $total_literals += 1;
                $i += 1;  // Skip the escaped double-quote.
            }
            else {
                $total_literals += 3;
                $i += 3;  // Skip the escaped hex char.
            }
        }

        $total_literals += 1;
        $total_values += 1;
    }
    // echo "--> LITERALS: ${total_literals}\tVALUES: ${total_values}\tTOTAL: " . ($total_literals - $total_values) . PHP_EOL;
}

echo "LITERALS: ${total_literals}\tVALUES: ${total_values}\tTOTAL: " . ($total_literals - $total_values) . PHP_EOL;