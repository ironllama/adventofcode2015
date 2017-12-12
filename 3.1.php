<?php

    $input = file_get_contents("3.1.in.txt");

    //$input = "^v^v^v^v^v";

    $x = 0;
    $y = 0;
    // Tracking visited coordinates with this array. Start with the first house.
    $visited = ["0,0"];

    // Let's split out the string into an array, with one cell per direction to move.
    $allMoves = str_split($input);
    for ($i = 0; $i < count($allMoves); $i++) {
        // Determine what to do with the new direction -- it'll either change x or y.
        if ($allMoves[$i] == '^') $y++;
        else if ($allMoves[$i] == 'v') $y--;
        else if ($allMoves[$i] == '>') $x++;
        else if ($allMoves[$i] == '<') $x--;

        // Create the new coordinate string.
        $newCoords = $x . "," . $y;

        // Check of the new coordinate string exists already in the visited array.
        if (! in_array($newCoords, $visited)) $visited[] = $newCoords;
    }

    echo("Total unique houses: " . (count($visited)));
?>