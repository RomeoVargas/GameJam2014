<?php
class StageSetting extends AppModel
{
    const FIRST_WORLD_ID = 1;
    const FIRST_WORLD_SEQ = 1;
    const FIRST_STAGE_PART = 1;
    const LAST_STAGE_PART = 3;

    protected static function getLevelSettingId($world_id, $world_sequence)
    {
        $db = DB::conn();
        $level_setting_id = $db->value('SELECT id FROM level_setting WHERE world_setting_id = ? AND world_sequence = ?', array($world_id, $world_sequence));
        return $level_setting_id;
    }

    public static function getById($stage_setting_id)
    {
        $db = DB::conn();
        $stage_setting = $db->row('SELECT * FROM stage_setting WHERE id = ?', array($stage_setting_id));
        return new self($stage_setting);
    }

    public static function get(array $stage_info)
    {
        $db = DB::conn();
        $level_setting_id = self::getLevelSettingId($stage_info['world_id'], $stage_info['world_seq']);
        $stage_setting = $db->row('SELECT * FROM stage_setting WHERE level_setting_id = ? AND part = ?', array($level_setting_id, $stage_info['stage_part']));
        return new self($stage_setting);
    }

    public function isLastPart()
    {
        return ($this->part == self::LAST_STAGE_PART);
    }

    public function getLevelSetting()
    {
        $db = DB::conn();
        $level_setting = $db->row('SELECT * FROM level_setting WHERE id = ?', array($this->level_setting_id));
        return new self($level_setting);
    }
}