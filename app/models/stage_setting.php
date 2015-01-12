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

    protected function convertEnemyTargetRange($target_range)
    {
        $range_plot = explode(',', $target_range);
        foreach ($range_plot as $key => $plot) {
            $plot_values = explode('_', $plot);
            $plot_values[0] = (-1) * $plot_values[0];
            $range_plot[$key] = implode('_', $plot_values);
        }
        return implode(',', $range_plot);
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

    public function getEnemyUnit($unit_id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM enemy_plot_setting WHERE stage_setting_id = ? AND unit_id = ?', array($this->id, $unit_id));
        $unit = new Unit($row);
        $enemy_unit = $unit->get($row['unit_id'], $row['current_lvl']);
        $enemy_unit->target_range = $this->convertEnemyTargetRange($enemy_unit->target_range);
        return $enemy_unit;
    }

    public function getEnemyUnits()
    {
        $db = DB::conn();
        $enemy_units = array();
        $enemies = $db->rows('SELECT * FROM enemy_plot_setting WHERE stage_setting_id = ?', array($this->id));
        if (!$enemies) {
            return null;
        }
        foreach ($enemies as $enemy) {
            $enemy_unit = $this->getEnemyUnit($enemy['unit_id']);
            $enemy_unit->coordinates = $enemy['coordinates'];
            $enemy_units[] = $enemy_unit;
        }
        return $enemy_units;
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