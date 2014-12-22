<?php
class StageSetting extends AppModel
{
    public function get($world_id, $world_sequence, $part)
    {
        $level_setting_id = $this->getLevelSettingId($world_id, $world_sequence);
        $db = DB::conn();
        $stage_setting = $db->row('SELECT * FROM stage_setting WHERE level_setting_id = ? AND part = ?', array($level_setting_id, $part));
        return new self($stage_setting);
    }

    private function getLevelSettingId($world_id, $world_sequence)
    {
        $db = DB::conn();
        $level_setting_id = $db->value('SELECT id FROM level_setting WHERE world_id = ? AND world_sequence = ?', array($world_id, $world_sequence));
        return $level_setting_id;
    }

    public static function getById($stage_setting_id)
    {
        $db = DB::conn();
        $stage_setting = $db->row('SELECT * FROM stage_setting WHERE id = ?', array($stage_setting_id));
        return new self($stage_setting);
    }

    public static function getLevelSetting($level_setting_id)
    {
        $db = DB::conn();
        $level_setting = $db->row('SELECT * FROM level_setting WHERE id = ?', array($level_setting_id));
        return new self($level_setting);
    }
}