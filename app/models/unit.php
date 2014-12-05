<?php
class Unit extends AppModel
{
    public static function getAll()
    {
        $db = DB::conn();
        $units = array();
        $rows = $db->rows('SELECT * FROM unit');
        foreach ($rows as $row) {
            $units[] = new self($row);
        }
        return $units;
    }
}