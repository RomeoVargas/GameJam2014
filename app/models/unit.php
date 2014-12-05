<?php
class Unit extends AppModel
{
    public static function getAll()
    {
        $db = DB::conn();
        $units = $db->rows('SELECT * FROM unit');
        return $units;
    }
}