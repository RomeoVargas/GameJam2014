<?php
class BattleController extends AppController
{
    public function index()
    {
        $player = $this->start();
        $player_units = $player->getUnitStorage()->getUnits();
        $active_leader_skill = $player->getUnitStorage()->getUnitLeader()->getLeaderSkill();
        $this->set(get_defined_vars());
    }
}