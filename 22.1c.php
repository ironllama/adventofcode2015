<?php

 // Using a Priority Queue instead of standard DFS-ish stack. Oddly, no real improvement in performance.

    // Spell => [Mana Cost, Damage, Armor, Health, Mana, Turns]
    $SP_IDX_MANA_COST = 0;
    $SP_IDX_DAMAGE = 1;
    $SP_IDX_ARMOR = 2;
    $SP_IDX_HP_GAIN = 3;
    $SP_IDX_MANA_GAIN = 4;
    $SP_IDX_TRNS_TOT = 5;
    $spells = [
        'Magic Missile' => [53, 4, 0, 0, 0, 0],
        'Drain' => [73, 2, 0, 2, 0, 0, 0],
        'Shield' => [113, 0, 7, 0, 0, 6],
        'Poison' => [173, 3, 0, 0, 0, 6],
        'Recharge' => [229, 0, 0, 0, 101, 5],
    ];

    $lowest_mana_used = PHP_INT_MAX;

    // Adapted from (https://www.php.net/manual/en/class.splpriorityqueue.php)
    class ReversePQ extends SplPriorityQueue
    {
        public function compare($priority1, $priority2)
        {
            if ($priority1 === $priority2) {
                return 0;
            }

            return $priority1 < $priority2 ? 1 : -1;  // Reversed the returns to reverse the queue.
        }
    }

    $all_playable_states = new ReversePQ();

    function spell_effect(&$state, $spell_name)
    {
        global $spells, $SP_IDX_MANA_COST, $SP_IDX_DAMAGE, $SP_IDX_HP_GAIN, $SP_IDX_MANA_GAIN, $SP_IDX_TRNS_TOT, $PRINT_OUTPUT;

        $this_spell = $spells[$spell_name];

        if ($this_spell[$SP_IDX_DAMAGE] > 0) {
            $state['boss_hp'] -= $this_spell[$SP_IDX_DAMAGE];
        }
        if ($this_spell[$SP_IDX_HP_GAIN] > 0) {
            $state['player_hp'] += $this_spell[$SP_IDX_HP_GAIN];
        }
        if ($this_spell[$SP_IDX_MANA_GAIN] > 0) {
            $state['player_mana'] += $this_spell[$SP_IDX_MANA_GAIN];
        }
    }

    function reconcile_all_effects(&$state)
    {
        $curr_spells_names = array_keys($state['spells_active']);
        for ($i = (count($curr_spells_names) - 1); $i >= 0; --$i) {
            spell_effect($state, $curr_spells_names[$i]);

            --$state['spells_active'][$curr_spells_names[$i]][1];  // Decrement the duration of the effect.

            if (0 === $state['spells_active'][$curr_spells_names[$i]][1]) {  // Remove when over.
                unset($state['spells_active'][$curr_spells_names[$i]]);
            }
        }
    }

    function is_over(&$state)
    {
        global $all_combos, $output_delimiter, $lowest_mana_used;

        if (0 >= $state['player_hp'] || 0 >= $state['boss_hp']) {
            if (0 >= $state['boss_hp']) {
                // if (!in_array($state['player_mana_used'], $all_combos)) {
                //     $all_combos[] = $state['player_mana_used'];
                // }
                if ($state['player_mana_used'] < $lowest_mana_used) {
                    $lowest_mana_used = $state['player_mana_used'];
                }
            }

            return true;
        }

        return false;
    }

    function all_player_turns(&$state)
    {
        global $all_playable_states, $spells, $lowest_mana_used, $SP_IDX_MANA_COST, $SP_IDX_TRNS_TOT;

        // $all_playable_states = [];

        // Player Turn
        reconcile_all_effects($state);
        if (!is_over($state)) {
            foreach ($spells as $spell_name => $this_spell) {  // Already pre-sorted in mana cost order, above.
                if ($lowest_mana_used < ($state['player_mana_used'] + $this_spell[$SP_IDX_MANA_COST])) {  // If this branch already spends more than current lowest, skip.
                    continue;
                }

                if (in_array($spell_name, $state['spells_active'])) {  // If spell is already active, skip.
                    continue;
                }

                $play_state = $state;  // Each branch has a copy of its own state.

                if ($play_state['player_mana'] >= $this_spell[$SP_IDX_MANA_COST]) {  // Enough mana?
                    $play_state['player_mana'] -= $this_spell[$SP_IDX_MANA_COST];
                    $play_state['player_mana_used'] += $this_spell[$SP_IDX_MANA_COST];

                    $spell_duration = $this_spell[$SP_IDX_TRNS_TOT];
                    if (0 == $spell_duration) {  // Instant spell?
                        spell_effect($play_state, $spell_name);
                    } else {  // Effect spell.
                        $play_state['spells_active'][$spell_name] = [$spell_name, $spell_duration];  // Just add to list -- it will gain affect during reconciliation.
                    }

                    // $all_playable_states[] = $play_state;
                    $all_playable_states->insert($play_state, $play_state['player_mana_used']);
                }

                // If there is not enough mana, no action is added. Not sure if this will cause a problem, but it's probably a dead-end death for player, anyway.
                // ALSO, seems to add a lot of memory. It seems there are A LOT of scenarios where the player runs out of mana.

                if (is_over($play_state)) {  // Does the spell kill the boss?
                    // return $all_playable_states;  // Short-circuit return since spells are in mana cost order and other branches will cost more.
                    return;
                }
            }
        }

        // return $all_playable_states;  // False means no spells cast -- no more mana!
        return;
    }

    function boss_turn(&$state)
    {
        global $spells, $SP_IDX_ARMOR;

        // Boss Turn
        reconcile_all_effects($state);
        if (!is_over($state)) {
            $player_armor = 0;
            if (array_key_exists('Shield', $state['spells_active'])) {
                $player_armor = $spells['Shield'][$SP_IDX_ARMOR];
            }
            $total_damage = $state['boss_damage'] - $player_armor;
            $state['player_hp'] -= $total_damage;

            if (!is_over($state)) {  // Does the boss kill the player?
                return true;
            }
        }

        return false;
    }

    $state = [
        // 'player_hp' => 10,
        // 'player_mana' => 250,
        // 'boss_hp' => 13,
        // 'boss_hp' => 14,
        // 'boss_hp' => 14,
        'player_hp' => 50,
        'player_mana' => 500,
        'boss_hp' => 55,
        'boss_damage' => 8,
        'player_mana_used' => 0,
        'spells_active' => [],
    ];

    $all_combos = [];

    function play_cycle(&$state)
    {
        global $all_playable_states;

        all_player_turns($state);  // Do the first player turn and seed $all_player_turns.

        while ($all_playable_states->valid()) {
            // $all_player_turns = all_player_turns($state);
            // foreach ($all_player_turns as $play_state) {
            $play_state = $all_playable_states->extract(); // Get the next possible state and play it out.
            if (!boss_turn($play_state)) {  // Check if the game ends during the boss's turn.
                continue;  // Game over. Someone died during the boss turn.
            }

            all_player_turns($play_state);  // Get the next possible states from this branch and add it to the $all_player_turns.
            // play_cycle($play_state);
        }
    }

    $time_start = microtime(true);
    play_cycle($state);

    echo "\nFINISHED: \n";
    // foreach ($all_combos as $combo) {
    //     echo 'MANA USED: ', $combo.PHP_EOL;
    //     if ($lowest_mana_used > $combo) {
    //         $lowest_mana_used = $combo;
    //     }
    // }
    echo "\nLOWEST: {$lowest_mana_used}\n";
    echo 'Total execution time in seconds: '.(microtime(true) - $time_start);
