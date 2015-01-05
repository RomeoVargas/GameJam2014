<?php
$response = array(
    'current_stage_info'    => $current_stage_info,
    'next_stage_info'       => $next_stage_info,
    'player'                => $player,
    'is_win'                => true,
    'exp_gained'            => $stage_setting->exp_gained,
    'loot_gained'           => $loot_gained
);