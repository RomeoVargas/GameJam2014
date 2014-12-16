<?php
class UnitStorage extends AppModel
{
    private $player;
    private $units;
    private $unit_leader_id;

    public function __construct($row)
    {
        $this->player = $row;
    }

    public function getUnits()
    {
        $db = DB::conn();
        $units = array();
        $rows = $db->rows('SELECT * FROM player_units WHERE player_id = ?', array($this->player->id));
        foreach ($rows as $row) {
            $row['player'] = $this->player;
            $unit = new Unit($row);
            $units[] = $unit->get($row['unit_id']);
        }
        return $units;
    }

    public function getUnit($unit_id)
    {
        $db = DB::conn();
        $unit = $db->row('SELECT * FROM player_units WHERE player_id = ? AND unit_id = ?', array($this->player->id, $unit_id));
        $row['player'] = $this->player;
        $unit = new Unit($row);
        return $unit->get($row['unit_id']);
    }

    public function getUnitLeaderId()
    {
        if ($this->unit_leader_id) {
            return $this->unit_leader_id;
        }
        $db = DB::conn();
        $unit_leader_id = $db->value('SELECT unit_leader_id FROM player WHERE id = ?', array($this->player->id));
        return $unit_leader_id;
    }
}