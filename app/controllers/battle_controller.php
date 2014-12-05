<?php
class BattleController extends AppController
{
    public function index()
    {
        $sample_unit = array(
            'id' => 1,
            'name' => 'johnny_bravo',
            'display_name' => 'Jack the giant slayer',
            'class' => 'warrior',
            'attack' => 1024,
            'defense' => 2048,
            'int' => 50,
            'agi' => 102,
            'str' => 500,
            'hp' => 103342,
            'target_range' => '0-1, 0-1, 0-1'
        );
        $this->set(get_defined_vars());
    }
}