<?php
class BattleController extends AppController
{
    public function index()
    {
        $player = $this->start();
        $player_units = $player->getUnitStorage()->getAll();
        $unit_leader_skill = $player->getUnitStorage()->getUnitLeaderSkill();
        $this->set(get_defined_vars());
    }
}