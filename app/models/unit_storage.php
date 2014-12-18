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

    public function getUnits()
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

    public function getUnitLeader()
    {
        if ($this->unit_leader) {
            return $this->unit_leader;
        }
        return $this->getUnit($this->player->unit_leader_id);
    }
}