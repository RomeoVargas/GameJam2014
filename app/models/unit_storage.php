<?php
class UnitStorage
{
    const WARRIOR_CLASS_ID = 1;
    const ARCHER_CLASS_ID = 2;
    const CLERIC_CLASS_ID = 3;
    const MAGE_CLASS_ID = 4;

    private static $player = null;

    public function __construct(Player $player)
    {
        if (is_null(self::$player)) {
            self::$player = $player;
        }
    }

    public function getClass($class_id)
    {
        $db = DB::conn();
        $class = $db->row('SELECT * FROM class WHERE id = ?', array($class_id));
        return new self($class);
    }

    public function getAll()
    {
        $db = DB::conn();
        $units = array();
        $rows = $db->rows('SELECT unit_id FROM player_units WHERE player_id = ?', array(self::$player->id));
        foreach ($rows as $row) {
            $units[] = $this->getUnitById($row['unit_id']);
        }
        return $units;
    }

    public function getUnitById($unit_id)
    {
        $db = DB::conn();
        $unit = $db->row('SELECT * FROM player_units p INNER JOIN unit u ON p.unit_id = u.id WHERE p.player_id = ? AND u.id = ?',
            array(self::$player->id, $unit_id));
        $unit = $this->getUnitStats($unit);
        return new self($unit);
    }

    public function getUnitStats(array $unit)
    {
        $unit_class = $this->getClass($unit['class_id']);
        $unit['int'] = ($unit['int'] + $unit_class->int_up_per_lvl) * $unit['current_lvl'];
        $unit['agi'] = ($unit['agi'] + $unit_class->agi_up_per_lvl) * $unit['current_lvl'];
        $unit['str'] = ($unit['str'] + $unit_class->str_up_per_lvl) * $unit['current_lvl'];
        $unit['vit'] = ($unit['vit'] + $unit_class->vit_up_per_lvl) * $unit['current_lvl'];
        $unit['atk'] = $this->getAttack($unit);
        $unit['def'] = $this->getDefense($unit['vit']);
        $unit['hp'] =  $this->getHealthPoint($unit['vit'], $unit['current_level']);
        return $unit;
    }

    public function getAttack(array $unit)
    {
        switch ($unit['class_id'])
        {
            case self::WARRIOR_CLASS_ID:
                $unit_stats = $unit['str'];
                break;
            case self::ARCHER_CLASS_ID:
                $unit_stats = $unit['agi'];
                break;
            case self::CLERIC_CLASS_ID:
            case self::MAGE_CLASS_ID:
                $unit_stats = $unit['int'];
                break;
            default:
                throw new ClassIdNotFoundException();
        }
        $attack = ceil(($unit['current_level']/2) + $unit_stats);
        return $attack;
    }

    public function getDefense($vit)
    {
        $defense = round(($vit/2) + max(($vit*0.3) * (($vit^2)/150) / 3));
        return $defense;
    }

    public function getHealthPoint($vit, $current_level)
    {
        $hp = round((($vit*10) + $current_level) + $vit);
        return $hp;
    }

    public function getUnitLeaderId()
    {
        $db = DB::conn();
        $unit_leader_id = $db->value('SELECT unit_leader_id FROM player WHERE id = ?', array(self::$player->id));
        return $unit_leader_id;
    }

    public function getUnitLeaderSkill()
    {
        $unit_leader_id = $this->getUnitLeaderId();
        $unit_leader = $this->getUnitById($unit_leader_id);
        $db = DB::conn();
        $unit_leader_skill = $db->row('SELECT * FROM unit_leader_skill WHERE id = ?', array($unit_leader->unit_leader_skill_id));
        if (!$unit_leader_skill) {
            return false;
        }
        $unit_leader_skill['unit_leader_id'] = $unit_leader_id;
        return $unit_leader_skill;
    }
}