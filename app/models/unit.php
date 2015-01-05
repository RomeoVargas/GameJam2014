<?php
class Unit extends AppModel
{
    const CLASS_WARRIOR = 'Warrior';
    const CLASS_ARCHER = 'Archer';
    const CLASS_CLERIC = 'Cleric';
    const CLASS_MAGE = 'Mage';

    public function __construct($row)
    {
        parent::__construct($row);
    }

    public function get($unit_id, $current_lvl)
    {
        $db = DB::conn();
        $unit = $db->row('SELECT * FROM unit WHERE id = ?', array($unit_id));
        $unit['current_lvl'] = (int) $current_lvl;
        $unit = $this->getStats($unit);
        return new self($unit);
    }

    public function getClass($class_id)
    {
        $db = DB::conn();
        $class = $db->row('SELECT * FROM unit_class WHERE id = ?', array($class_id));
        return new self($class);
    }

    public function getStats(array $unit)
    {
        $unit_class = $this->getClass($unit['class_id']);
        if ($unit['current_lvl'] > 1) {
            $unit['int'] = ($unit['int'] + $unit_class->int_up_per_lvl) * $unit['current_lvl'];
            $unit['agi'] = ($unit['agi'] + $unit_class->agi_up_per_lvl) * $unit['current_lvl'];
            $unit['str'] = ($unit['str'] + $unit_class->str_up_per_lvl) * $unit['current_lvl'];
            $unit['vit'] = ($unit['vit'] + $unit_class->vit_up_per_lvl) * $unit['current_lvl'];
        }
        $unit['atk'] = $this->getAttack($unit);
        $unit['def'] = $this->getDefense($unit['vit']);
        $unit['hp'] =  $this->getHealthPoint($unit['vit'], $unit['current_lvl']);
        return $unit;
    }

    public function getAttack(array $unit)
    {
        $unit_class = $this->getClass($unit['class_id'])->name;
        switch ($unit_class)
        {
            case self::CLASS_WARRIOR:
                $unit_stats = $unit['str'];
                break;
            case self::CLASS_ARCHER:
                $unit_stats = $unit['agi'];
                break;
            case self::CLASS_CLERIC:
            case self::CLASS_MAGE:
                $unit_stats = $unit['int'];
                break;
            default:
                throw new ClassIdNotFoundException();
        }
        $attack = ceil(($unit['current_lvl']/2) + $unit_stats);
        return $attack;
    }

    public function getDefense($vit)
    {
        $defense = round(($vit/2) + ceil(($vit*0.3) * (($vit^2)/150) / 3));
        return $defense;
    }

    public function getHealthPoint($vit, $current_level)
    {
        $hp = round((($vit*10) + $current_level) + $vit);
        return $hp;
    }

    public function getLeaderSkill()
    {
    }

}