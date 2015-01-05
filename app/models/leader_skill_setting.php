<?php
class LeaderSkillSetting extends AppModel
{
    const EFFECT_ATK_UP = 'ATK_UP';
    const EFFECT_DEF_UP = 'DEF_UP';
    const EFFECT_STR_UP = 'STR_UP';
    const EFFECT_INT_UP = 'INT_UP';
    const EFFECT_AGI_UP = 'AGI_UP';
    const EFFECT_VIT_UP = 'VIT_UP';
    const EFFECT_ALL_UP = 'ALL_UP';
    const EFFECT_FAST_BURST = 'FAST_BURST';

    public static function get($unit_leader_skill_id)
    {   
        $db = DB::conn();
        $unit_leader_skill = $db->row('SELECT * FROM unit_leader_skill WHERE id = ?', array($unit_leader_skill_id));
        return new self($unit_leader_skill);
    }
    public function apply(array $units)
    {
        foreach ($units as $unit) {
            switch ($this->skill_effect) {
                case self::EFFECT_ATK_UP:
                    $unit->atk = $unit->atk * $this->effect_qty;
                    break; 
                case self::EFFECT_DEF_UP:
                    $unit->def = $unit->def * $this->effect_qty;
                    break; 
                case self::EFFECT_STR_UP:
                    $unit->str = $unit->str + $this->effect_qty;
                    $unit->atk = $unit->getAttack(to_array($unit)); 
                    break;
                case self::EFFECT_INT_UP:
                    $unit->int = $unit->int + $this->effect_qty;
                    $unit->atk = $unit->getAttack(to_array($unit)); 
                    break;
                case self::EFFECT_AGI_UP:
                    $unit->agi = $unit->agi + $this->effect_qty;
                    $unit->atk = $unit->getAttack(to_array($unit)); 
                    break;
                case self::EFFECT_VIT_UP:
                    $unit->vit = $unit->vit + $this->effect_qty;                
                    $unit->def = $unit->getDefense($unit->vit);
                    $unit->hp =  $unit->getHealthPoint($unit->vit, $unit->current_lvl);             
                    break; 
                case self::EFFECT_ALL_UP:
                    $unit->str = $unit->str + $this->effect_qty;
                    $unit->int = $unit->int + $this->effect_qty;
                    $unit->agi = $unit->agi + $this->effect_qty;
                    $unit->vit = $unit->vit + $this->effect_qty;
                    $unit->atk = $unit->getAttack(to_array($unit));   
                    $unit->def = $unit->getDefense($unit->vit);
                    $unit->hp =  $unit->getHealthPoint($unit->vit, $unit->current_lvl); 
                case self::EFFECT_FAST_BURST:
                    $unit->burst = ceil($unit->burst - ($unit->burst * $this->effect_qty));
                    break;
            }
        }
        return $units;
    }
}