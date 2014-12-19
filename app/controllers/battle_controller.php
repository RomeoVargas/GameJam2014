<?php
class BattleController extends AppController
{
    public function index()
    {
        $player = $this->start();
        $player_units = $player->getUnitStorage()->getPlayerUnits();
        $stage_setting_id = $player->getStageSetting()->get(Param::get('world_id', 1), Param::get('world_seq', 1), Param::get('part', 1))->id;
        $enemy_units = $player->getUnitStorage()->getEnemyUnits($stage_setting_id);
        $active_leader_skill = $player->getUnitStorage()->getUnitLeader()->getLeaderSkill();
        $this->set(get_defined_vars());
    }
}