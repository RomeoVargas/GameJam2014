<?php
class BattleController extends AppController
{
    public function index()
    {
        $player = $this->start();
        $unit_storage = $player->getUnitStorage();
        $active_leader_skill = LeaderSkillSetting::get($unit_storage->getUnitLeader()->unit_leader_skill_id);
        $player_units = $active_leader_skill->apply($unit_storage->getUnits(UnitStorage::IS_MAIN_UNIT));

        $world_info = array(
            'world_id'      => (int) Param::get('world_id', StageSetting::FIRST_WORLD_ID),
            'world_seq'     => (int) Param::get('level', StageSetting::FIRST_WORLD_SEQ)
        );
        for ($stage = StageSetting::FIRST_STAGE_PART; $stage <= StageSetting::LAST_STAGE_PART; $stage++) {
            $stage_info['stage_part'] = $stage;
            $stage_info = array_merge($world_info, $stage_info);
            $stage_setting = StageSetting::get($stage_info);
            $enemy_units = $unit_storage->getEnemyUnits($stage_setting->id);
            $level_boss = null;
            $enemy_unit_leader_skill = null;
            if ($stage_setting->isLastPart()) {
                $level_boss = $unit_storage->getEnemyUnit($stage_setting->id, $stage_setting->getLevelSetting()->level_boss_id);
                $enemy_unit_leader_skill = LeaderSkillSetting::get($level_boss->unit_leader_skill_id);
                $enemy_units = $enemy_unit_leader_skill->apply($enemy_units);
            }
            $world_info['stages'][] = array(
                'stage_part'            => $stage,
                'level_boss'			=> $level_boss,
                'enemy_leader_skill'	=> $enemy_unit_leader_skill,
                'enemy_units'           => $enemy_units
            );
        }

        $this->set(get_defined_vars());
    }

    public function stage_end()
    {
        $player = $this->start();
        $is_win = Param::get('is_win', false);
        $current_stage_info = array(
            'world_id'      => (int) Param::get('world_id', StageSetting::FIRST_WORLD_ID),
            'world_seq'     => (int) Param::get('world_seq', StageSetting::FIRST_WORLD_SEQ),
            'stage_part'    => (int) Param::get('stage_part', StageSetting::FIRST_STAGE_PART)
        );
        $stage_setting = StageSetting::get($current_stage_info);
        $next_stage_info = null;
        if (!$stage_setting->isLastPart()) {
            $next_stage_info = array_merge(
                $current_stage_info,
                array('stage_part' => ($stage_setting->part + 1))
            );
        }
        if (!$is_win) {
            $this->set(get_defined_vars());
            $this->render('stage_failed');
        }
        $player->gainExp($stage_setting->exp_gained);
        $possible_loot_gained = StageLootSetting::get($stage_setting->id);
        if ($possible_loot_gained) {
            $loot_gained = array();
            foreach ($possible_loot_gained as $loot) {
                if ($loot->give($player)) {
                    $loot_gained[] = $loot;
                }
            }
        }
        $this->set(get_defined_vars());
    }
}