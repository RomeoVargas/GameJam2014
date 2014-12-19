<?php
class UnitStorage
{
    private $player;
    private $units;
    private $unit_leader;

    public function __construct($row)
    {
        $this->player = $row;
    }

    public function getUnit($unit_id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM player_units WHERE player_id = ? AND unit_id = ?', array($this->player->id, $unit_id));
        $row['player'] = $this->player;
        $unit = new Unit($row);
        return $unit->get($row['unit_id'], $row['current_lvl']);
    }

    public function getPlayerUnits()
    {
        $db = DB::conn();
        $units = array();
        $rows = $db->rows('SELECT unit_id FROM player_units WHERE player_id = ?', array($this->player->id));
        foreach ($rows as $row) {
            $units[] = $this->getUnit($row['unit_id']);
        }
        $this->units = $units;
        return $units;
    }

    public function getEnemyUnits($stage_setting_id)
    {
        $db = DB::conn();
        $enemy_units = array();
        $enemy_unit_ids = explode(',',$db->value('SELECT unit_ids FROM stage_setting WHERE id = ?', array($stage_setting_id)));
        foreach ($enemy_unit_ids as $enemy_unit_id) {
            $enemy_units[] = $this->getUnit($enemy_unit_id);
        }
        return $enemy_units;
    }

    public function getUnitLeader()
    {
        if ($this->unit_leader) {
            return $this->unit_leader;
        }
        return $this->getUnit($this->player->unit_leader_id);
    }
}