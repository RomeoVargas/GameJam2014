<?php
class Player extends AppModel
{
    const BASE_LEVEL_UP_EXP = 180;
    const LEVEL_CAP_MULTIPLIER = 360;

    private static function getPlayerId($client_id)
    {
        $db = DB::conn();
        $player_id = $db->value('SELECT player_id FROM player_id_finder WHERE client_id = ?', array($client_id));
        return $player_id;
    }

    public static function getByClientId($client_id)
    {
        $player_id = self::getPlayerId($client_id);
        $db = DB::conn();
        $player = $db->row('SELECT * FROM player WHERE id = ?', array($player_id));
        $stage_setting = StageSetting::getById($player['stage_setting_id']);
        $level_setting = $stage_setting->getLevelSetting();
        $player['last_world'] = $level_setting->world_setting_id;
        $player['last_world_sequence'] = $level_setting->world_sequence;
        $player['last_stage_part'] = $stage_setting->part;
        unset($player['stage_setting_id']);
        return new self($player);
    }

    protected function isLevelUp($exp)
    {
        $max_exp_per_level = self::BASE_EXP;
        if ($this->level != 1) {
            $max_exp_per_level = $max_exp_per_level + ($this->level * self::LEVEL_CAP_MULTIPLIER);
        }
        return ($exp >= $max_exp_per_level);
    }

    public function gainExp($exp_gained)
    {
        $db = DB::conn();
        $new_level = $this->level;
        $total_exp = $this->exp + $exp_gained;
        if ($this->isLevelUp($total_exp)) {
            $new_level++;
            $total_exp = 0;
        }
        $db->update('player', array('level' => $new_level, 'exp' => $total_exp), array('id' => $this->id));
    }

    public function addGold($gold_coin)
    {
        $db = DB::conn();
        $new_gold_num = $this->gold + $gold_coin;
        $db->update('player', array('gold' => $new_gold_num), array('id' => $this->id));        
    }

    public function getUnitStorage()
    {
        return new UnitStorage($this);
    }
}