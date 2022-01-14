<?php

    // NOTE: This works well with the small sample data, but DOES NOT WORK (runs forever) with the real input.
    // However, it includes all the fancy output and whatnot, so I left it in place.

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

    // $PRINT_OUTPUT = true;
    $PRINT_OUTPUT = false;

    $output_delimiter = "================================================\n";
    // $min_mana_spent = PHP_INT_MAX;

    function spell_effect(&$state, $spell_name)
    {
        global $spells, $SP_IDX_MANA_COST, $SP_IDX_DAMAGE, $SP_IDX_HP_GAIN, $SP_IDX_MANA_GAIN, $SP_IDX_TRNS_TOT, $PRINT_OUTPUT;

        $this_spell = $spells[$spell_name];
        if ($PRINT_OUTPUT) {
            $state['output'] .= "{$spell_name}";
        }

        if ($this_spell[$SP_IDX_DAMAGE] > 0) {
            $state['boss_hp'] -= $this_spell[$SP_IDX_DAMAGE];
            if ($PRINT_OUTPUT) {
                if ('Poison' == $spell_name) {
                    $state['output'] .= " deals {$this_spell[$SP_IDX_DAMAGE]} damage;";
                } else {
                    $state['output'] .= ", dealing {$this_spell[$SP_IDX_DAMAGE]} damage";
                    if ('Magic Missile' == $spell_name) {
                        $state['output'] .= ".\n";
                    }
                }
            }
        }
        // if ($this_spell[$SP_IDX_ARMOR] > 0) {
        //     // $player_armor = $this_spell[$SP_IDX_ARMOR];
        //     $state['player_armor'] = $this_spell[$SP_IDX_ARMOR];
        // }
        if ($this_spell[$SP_IDX_HP_GAIN] > 0) {
            $state['player_hp'] += $this_spell[$SP_IDX_HP_GAIN];
            if ($PRINT_OUTPUT) {
                $state['output'] .= " and healing {$this_spell[$SP_IDX_HP_GAIN]} hit points.\n";
            }
        }
        if ($this_spell[$SP_IDX_MANA_GAIN] > 0) {
            $state['player_mana'] += $this_spell[$SP_IDX_MANA_GAIN];
            if ($PRINT_OUTPUT) {
                $state['output'] .= " provides {$this_spell[$SP_IDX_MANA_GAIN]} mana;";
            }
        }
    }

    function reconcile_all_effects(&$state)
    {
        global $spells, $SP_IDX_ARMOR, $PRINT_OUTPUT;

        $curr_spells_names = array_keys($state['spells_active']);
        // foreach ($spells_active as $spell_name => $turns_left) {
        for ($i = (count($curr_spells_names) - 1); $i >= 0; --$i) {
            spell_effect($state, $curr_spells_names[$i]);

            --$state['spells_active'][$curr_spells_names[$i]][1];  // Decrement the duration of the effect.

            if ($PRINT_OUTPUT) {
                if ('Shield' == $curr_spells_names[$i]) {
                    $state['output'] .= "'s timer is now {$state['spells_active'][$curr_spells_names[$i]][1]}.\n";
                    if (0 == $state['spells_active'][$curr_spells_names[$i]][1]) {
                        $state['output'] .= "{$curr_spells_names[$i]} wears off, decreasing armor by {$spells[$curr_spells_names[$i]][$SP_IDX_ARMOR]}.\n";
                    }
                } elseif ('Recharge' == $curr_spells_names[$i] || 'Poison' == $curr_spells_names[$i]) {
                    $state['output'] .= " its timer is now {$state['spells_active'][$curr_spells_names[$i]][1]}.\n";
                    if (0 == $state['spells_active'][$curr_spells_names[$i]][1]) {
                        $state['output'] .= "{$curr_spells_names[$i]} wears off.\n";
                    }
                }
            }

            if (0 === $state['spells_active'][$curr_spells_names[$i]][1]) {  // Remove when over.
                unset($state['spells_active'][$curr_spells_names[$i]]);
                // array_splice($spells_active, $i, 1);
            }
        }
    }

    function is_over(&$state)
    {
        global $all_combos, $output_delimiter, $PRINT_OUTPUT;

        if (0 >= $state['player_hp'] || 0 >= $state['boss_hp']) {
            if (0 >= $state['boss_hp']) {
                if ($PRINT_OUTPUT) {
                    $state['output'] .= "WIN!\n";
                }
                $all_combos[] = [$state['player_mana_used'], $state['combos'], $state['output']];
            } else {
                if ($PRINT_OUTPUT) {
                    $state['output'] .= "LOSE! COMBOS: Player: {$state['player_hp']}  Boss: {$state['boss_hp']} Spells: ".implode(', ', $state['combos']).PHP_EOL;
                    echo $state['output'];
                    echo $output_delimiter;
                }
            }

            return true;
        }

        return false;
    }

    function status(&$state)
    {
        global $spells, $SP_IDX_ARMOR, $PRINT_OUTPUT;

        $player_armor = 0;
        if (array_key_exists('Shield', $state['spells_active'])) {
            $player_armor = $spells['Shield'][$SP_IDX_ARMOR];
        }
        $state['output'] .= "- Player has {$state['player_hp']} hit points, {$player_armor} armor, {$state['player_mana']} mana.\n";
        $state['output'] .= "- Boss has {$state['boss_hp']} hit points.\n";
    }

    function all_player_turns(&$state)
    {
        global $spells, $SP_IDX_MANA_COST, $SP_IDX_TRNS_TOT, $PRINT_OUTPUT;

        $all_playable_turns = [];

        // Player Turn
        foreach ($spells as $spell_name => $this_spell) {
            $play_state = $state;  // Each branch has a copy of its own state.

            if (in_array($spell_name, $state['spells_active'])) {  // If spell is already active, cast a different one.
                continue;
            }

            if ($play_state['player_mana'] >= $this_spell[$SP_IDX_MANA_COST]) {  // Enough mana?
                $play_state['combos'][] = $spell_name;
                $play_state['player_mana'] -= $spells[$spell_name][$SP_IDX_MANA_COST];
                $play_state['player_mana_used'] += $spells[$spell_name][$SP_IDX_MANA_COST];
                if ($PRINT_OUTPUT) {
                    $play_state['output'] .= 'Player casts ';
                }

                $spell_duration = $spells[$spell_name][$SP_IDX_TRNS_TOT];
                if (0 == $spell_duration) {
                    spell_effect($play_state, $spell_name);
                } else {
                    $play_state['spells_active'][$spell_name] = [$spell_name, $spell_duration];
                    if ($PRINT_OUTPUT) {
                        $play_state['output'] .= $spell_name.".\n";
                    }
                }
            } else {
                $play_state['output'] .= "Player has no more mana!\n";
            }

            // return true;  // Short-circuit to loop -- don't cast any more spells.
            $all_playable_turns[] = $play_state;
        }

        return $all_playable_turns;  // False means no spells cast -- no more mana!
    }

    function boss_turn(&$state)
    {
        global $spells, $SP_IDX_ARMOR, $PRINT_OUTPUT;

        // Boss Turn
        if ($PRINT_OUTPUT) {
            $state['output'] .= "\n-- Boss turn --\n";
            status($state);
        }
        reconcile_all_effects($state);
        if (is_over($state)) {
            return false;
        }

        $player_armor = 0;
        if (array_key_exists('Shield', $state['spells_active'])) {
            $player_armor = $spells['Shield'][$SP_IDX_ARMOR];
        }
        $total_damage = $state['boss_damage'] - $player_armor;
        $state['player_hp'] -= $total_damage;
        if ($PRINT_OUTPUT) {
            $state['output'] .= "Boss attacks for {$state['boss_damage']} - {$player_armor} = {$total_damage} damage.\n";
        }

        return true;
    }

    // $boss_hit_points = 13;
    // // $boss_hit_points = 14;
    // $boss_damage = 8;

    // // $boss_hit_points = 55;
    // // $boss_damage = 8;
    // $player_hit_points = 10;
    // $player_mana = 250;

    $state = [
        'player_hp' => 10,
        'player_mana' => 250,
        // 'boss_hp' => 13,
        // 'boss_hp' => 14,
        'boss_hp' => 14,
        // 'player_hp' => 50,
        // 'player_mana' => 500,
        // 'boss_hp' => 55,
        'boss_damage' => 8,
        'player_mana_used' => 0,
        'spells_active' => [],
        'combos' => [],
        'output' => '',
        // 'next_turn' => 0,
    ];

    $all_combos = [];

    function play_cycle(&$new_state)
    {
        global $spells, $all_combos, $SP_IDX_MANA_COST, $SP_IDX_DAMAGE, $SP_IDX_ARMOR, $SP_IDX_HP_GAIN, $SP_IDX_MANA_GAIN, $SP_IDX_TRNS_TOT, $PRINT_OUTPUT;

        $state = $new_state;  // Make a copy of the state before changing it!

        if ($PRINT_OUTPUT) {
            $state['output'] .= "\n-- Player turn --\n";
            status($state);
        }
        reconcile_all_effects($state);
        if (is_over($state)) {
            return;
            // return $all_playable_turns;
            // continue;
        }

        $all_player_turns = all_player_turns($state);
        foreach ($all_player_turns as $play_state) {
            if (is_over($play_state)) {  // Does the spell kill the boss?
                // return;
                continue;
            }

            if (!boss_turn($play_state)) {  // If the game didn't end during the boss's turn (reconcile_all_effects)
                continue;
            }

            if (is_over($play_state)) {  // Does the boss kill the player?
                // return;
                continue;
            }

            if ($PRINT_OUTPUT) {
                $play_state['output'] .= 'END OF CYCLE: active_spells: '.implode(', ', array_keys($play_state['spells_active'])).PHP_EOL;
            }
            play_cycle($play_state);
        }
    }

    $PRINT_OUTPUT = true;  // Comment out to turn off verbose output.

    $time_start = microtime(true);
    play_cycle($state);

    echo "\nFINISHED: \n";
    $lowest_mana_used = PHP_INT_MAX;
    foreach ($all_combos as $combo) {
        echo $combo[2];
        echo PHP_EOL;
        echo 'MANA USED: ', $combo[0], '  SPELLS: ', implode(', ', $combo[1]), PHP_EOL;
        echo $output_delimiter;
        if ($lowest_mana_used > $combo[0]) {
            $lowest_mana_used = $combo[0];
        }
    }
    echo "\nLOWEST: {$lowest_mana_used}\n";
    echo 'Total execution time in seconds: '.(microtime(true) - $time_start);
    // foreach ($all_combos as $combo) {
    //     echo 'MANA USED: ', $state['player_mana'] - $combo[0], '  MANA LEFT: ', $combo[0], '  SPELLS: ', implode(', ', $combo[1]), PHP_EOL;
    // }
