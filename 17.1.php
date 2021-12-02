<?php
$allStringLines = [
    "20",
    "15",
    "10",
    "5",
    "5",
];
// $allStringLines = explode("\n", file_get_contents('14.in.txt'));
$allIntLines = array_map(fn($line) => intval($line), $allStringLines);
// print_r($allIntLines);

$storage_goal = 25;
$storage_goal_combo = [];

function get_another($visited, $score)
{
    global $allIntLines, $storage_goal, $storage_goal_combo;

    if (count($visited) > 0) $last_visited = $visited[count($visited) - 1];
    else $last_visited = 0;

    // foreach ($allIntLines as $this_int) {
    for ($i = $last_visited; $i < count($allIntLines); $i++) {
        $new_visited = $visited;
        $new_visited[] = $i;

        // echo "SCORE: ${score} + ${removed}".PHP_EOL;
        $new_score = $score + $allIntLines[$i];
        // $new_path = $path . ' + ' . $removed;
        // $new_path = $path;
        // $new_path[] = $i;  // Storing indexes of used items.
        // echo "SCORE: ${new_score} PATH: ${new_path}".PHP_EOL;

        if ($new_score < $storage_goal) {
            // get_another($new_visited, $new_score, $new_path);
            get_another($new_visited, $new_score);
        }
        else if ($new_score === $storage_goal) {
            // natsort($new_path);
            // if (!in_array($new_path)) $storage_goal_combo[] = $new_path;
            $storage_goal_combo[] = $new_visited;
        }
    }
}
get_another([], 0);

print_r($storage_goal_combo);
echo "NUM: ".count($storage_goal_combo).PHP_EOL;