<?php
class BattleController extends AppController
{
    public function index()
    {
        $player = $this->start();
        $unit_storage = $player->getUnitStorage();
        $units = $unit_storage->getAll();
        $unit_leader_skill = $unit_storage->getUnitLeaderSkill();
        $this->set(get_defined_vars());
    }
}