<?php
class BattleController extends AppController
{
    public function index()
    {
        $units = Unit::getAll();
        $this->set(get_defined_vars());
    }
}