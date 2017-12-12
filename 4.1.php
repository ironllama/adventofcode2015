<?php

$secretKey = "yzbqklnj";

// The for loop has no condition (left blank), it will always default to true, meaning infinite looooooop.
// Make sure you have the proper controls inside the loop to self-terminate (explicitly stop) the loop.
for ($i = 0; ; $i++) {
    // PHP makes the MD5 so easy!
    $thisHash = md5($secretKey . $i);

    // Check the first 5 characters of the result.
    if (substr($thisHash, 0, 5) === '00000') {
        echo ("Found Match! [" + $i + "]");
        break;  // Yay, self-terminating!
    }
}

?>