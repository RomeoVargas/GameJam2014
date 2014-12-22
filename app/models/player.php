<?php
class Player extends AppModel
{
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
        $level_setting = StageSetting::getLevelSetting($stage_setting['level_setting_id']);
        $player['world_setting_id'] = $level_setting['world_id'];
        $player['level_setting_id'] = $level_setting['world_sequence'];
        return new self($player);
    }

    public function getUnitStorage()
    {
        return new UnitStorage($this);
    }
}