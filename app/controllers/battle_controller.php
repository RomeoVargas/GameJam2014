<?php
class BattleController extends AppController
{
    public function index()
    {
        $units = Unit::getAll();
        $player_units = array();
        foreach ($units as $unit) {
            $player_units[] = array(
                'id'            => (int) $unit->id,
                'name'          => $unit->name,
                'display_name'  => $unit->display_name,
                'class_id'      => (int) $unit->class_id,
                'level'         => (int) $unit->level,
                'int'           => (int) $unit->int,
                'agi'           => (int) $unit->agi,
                'str'           => (int) $unit->str,
                'vit'           => (int) $unit->vit,
                'burst'         => (int) $unit->burst,
                'target_range'  => $unit->target_range
            );
        }
        $this->set(get_defined_vars());
    }
}