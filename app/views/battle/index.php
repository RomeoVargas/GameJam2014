<?php
$response = array(
    'player' => to_array($player),
    'units' => $player_units,
    'unit_leader_skill' => $active_leader_skill,
    'enemy_units' => $enemy_units
);