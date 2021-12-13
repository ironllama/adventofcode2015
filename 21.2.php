<?php

$weapons = [
    ['Dagger', 8, 4, 0],
    ['Shortsword', 10, 5, 0],
    ['Warhammer', 25, 6, 0],
    ['Longsword', 40, 7, 0],
    ['Greataxe', 74, 8, 0],
];

$armor = [
    ['No Armor', 0, 0, 0],
    ['Leather', 13, 0, 1],
    ['Chainmail', 31, 0, 2],
    ['Splintmail', 53, 0, 3],
    ['Bandedmail', 75, 0, 4],
    ['Platemail', 102, 0, 5],
];

$rings = [
    ['No ring', 0, 0, 0],
    ['Damage +1', 25, 1, 0],
    ['Damage +2', 50, 2, 0],
    ['Damage +3', 100, 3, 0],
    ['Defense +1', 20, 0, 1],
    ['Defense +2', 40, 0, 2],
    ['Defense +3', 80, 0, 3],
];

$my_hp = 100;

$boss_hp = 100;
$boss_armor = 2;
$boss_dam = 8;

// $cheapest_cost = 0;
// $cheapest_combo = [];
$most_cost = 0;  // Just changed the name of the above variables. Searched and replaced.
$most_combo = [];
foreach (range(4, 8) as $dam) {
    foreach (range(0, 5) as $arm) {
        foreach ($rings as $one_ring) {
            foreach ($rings as $two_ring) {
                $total_dam = $dam + $one_ring[2] + $two_ring[2];
                $total_arm = $arm + $one_ring[3] + $two_ring[3];

                $my_final_dam = $total_dam - $boss_armor;
                if ($total_dam <= $boss_armor) {
                    $total_dam = 1;
                }

                $boss_final_dam = $boss_dam - $total_arm;
                if ($boss_dam <= $total_arm) {
                    $boss_final_dam = 1;
                }

                $turns_to_win = ceil($boss_hp / $my_final_dam);
                $turns_to_lose = ceil($my_hp / $boss_final_dam);

                if ($turns_to_win > $turns_to_lose) {
                    $my_weapon = array_values(array_filter($weapons, function ($x) use ($dam) { return $x[2] === $dam; }))[0];
                    $my_armor = array_values(array_filter($armor, function ($x) use ($arm) { return $x[3] === $arm; }))[0];

                    $cost = $my_weapon[1] + $my_armor[1] + $one_ring[1] + $two_ring[1];

                    if ($cost > $most_cost) {  // Changed this.
                        $most_cost = $cost;
                        $most_combo = [$my_weapon, $my_armor, $one_ring, $two_ring];
                    }

                    // echo "POSS: DAM: {$dam} ARMOR: {$arm} TTW: {$turns_to_win} TTL: {$turns_to_lose} COST: {$cost}".PHP_EOL;
                    // print_r($my_weapon);
                    // print_r($my_armor);
                }
            }
        }
    }
}
echo "MOST: {$most_cost}".PHP_EOL;
print_r($most_combo);
