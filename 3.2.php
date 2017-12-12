<?php

    $input = file_get_contents("3.1.in.txt");

    // Test values
    //$input = "^v^v^v^v^v";
    //$input = "^v";
    //$input = "^>v<";

    // Variables to track current location for each Santa and Robot
    $santaX = 0;
    $santaY = 0;
    $robotX = 0;
    $robotY = 0;
    $visited = ["0,0"];     // Tracking visited coordinates with this array. Start with the first house.

    // Let's split out the string into an array, with one cell per direction to move.
    $allMoves = str_split($input);
    for ($i = 0; $i < count($allMoves); $i++) {
        // Santa gets 0, Robot gets 1, Santa gets 2, Robot gets 3, etc. Santa gets evens. Robot gets odds.
        // The x and y are reused for calculating either one -- almost like an inline function.
        if ($i % 2 == 0) {  
            $x = $santaX;
            $y = $santaY;
        }
        else {
            $x = $robotX;
            $y = $robotY;
        }
        
        // Determine what to do with the new direction -- it'll either change x or y.
        if ($allMoves[$i] == '^') $y++;
        else if ($allMoves[$i] == 'v') $y--;
        else if ($allMoves[$i] == '>') $x++;
        else if ($allMoves[$i] == '<') $x--;

        // Create the new coordinate string.
        $newCoords = $x . "," . $y;

        // Check of the new coordinate string exists already in the visited array.
        if (! in_array($newCoords, $visited)) $visited[] = $newCoords;

        // After we are finished calculating with x and y, we reassign them back to the
        // respective context, Santa or Robot.
        if ($i % 2 == 0) {
            $santaX = $x;
            $santaY = $y;
        }
        else {
            $robotX = $x;
            $robotY = $y;
        }
    }

    echo("Total unique houses: " . (count($visited)));
?>