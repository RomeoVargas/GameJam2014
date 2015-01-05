<?php
class StageLootSetting extends AppModel
{
    const MIN_DICE_VALUE = 1;
    const CONTENT_TYPE_WARRIOR = 'Warrior';
    const CONTENT_TYPE_WEAPON = 'Weapon';
    const CONTENT_TYPE_ARMOR = 'Armor';
    const CONTENT_TYPE_COIN = 'Coins';

    protected function isLootGained($individual_probability)
    {
        $success_value = ceil(self::MIN_LOOT_RAND / $individual_probability);
        $dice = rand(self::MIN_LOOT_RAND, $success_value);
        return $dice == $success_value;
    }

    protected function getTotalWeight($stage_setting_id)
    {
        $db = DB::conn();
        $total_weight = $db->value('SELECT SUM(individual_weight) FROM stage_loot_setting WHERE stage_setting_id = ?', 
            array($stage_setting_id));
        return $total_weight;
    }

    protected function getIndividualProbability($stage_setting_id, $individual_weight)
    {
        $total_weight = $this->getTotalWeight($stage_setting_id);
        $individual_probability = $individual_weight/$total_weight;
        return $individual_probability;
    }

    public static function get($stage_setting_id)
    {
        $db = DB::conn();
        $possible_loot_gained = array();
        $rows = $db->rows('SELECT * FROM stage_loot_setting WHERE stage_setting_id = ?', 
            array($stage_setting_id));
        foreach ($rows as $row) {
            $possible_loot_gained[] = new self($row);
        }
        return $possible_loot_gained;
    }

    public function give(Player $player)
    {
        $individual_probability = $this->getIndividualProbability($this->stage_setting_id, $this->individual_weight);
        $is_loot_gained = $this->isLootGained($individual_probability);
        if ($is_loot_gained) {
            $db = DB::conn();
            $db->begin();
            switch ($this->content_type) {
                case self::CONTENT_TYPE_COIN:
                    $player->addGold((int) $this->content);
                    break;
                case self::CONTENT_TYPE_WARRIOR: 
                    $unit_storage = $player->getUnitStorage();
                    $unit_storage->addUnit($this->content_id);
                    break;                
                default:
                    $params = array(
                        'player_id'     => $player->id,
                        'item_id'       => $this->content_id,
                        'num_refined'   => $this->num_enhanced
                    );
                    $item_in_inventory = $db->search('player_inventory', 
                        'player_id = ? AND item_id =? AND num_refined = ?',
                        array($player->id, $this->content_id, $this->num_enhanced)
                    );
                    if ($item_in_inventory) {
                        $new_item_qty = $item_in_inventory->item_qty + $this->content_num;
                        $db->update('player_inventory', array('item_qty' => $new_item_qty), $params);
                    } else {   
                        $params['item_qty'] = $this->content_num;                 
                        $db->insert('player_inventory', $params);                        
                    }
                    break;
            }
            $db->commit();
        }
        return $is_loot_gained;
    }
}