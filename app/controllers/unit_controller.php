<?php
class UnitController extends AppController
{
    public function index()
    {
        $player = $this->start();
        $unit_storage = $player->getUnitStorage();
        $main_units = $unit_storage->getUnits(UnitStorage::IS_MAIN_UNIT);
        $stock_units = $unit_storage->getUnits();
        $unit_leader = $unit_storage->getUnitLeader();
        $active_leader_skill = $unit_leader->getLeaderSkill();
        $this->set(get_defined_vars());
    }
}