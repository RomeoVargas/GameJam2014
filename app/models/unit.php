<?php
class Unit extends AppModel
{
    public static function getAll()
    {
        $db = DB::conn();
        $rows = $db->rows('SELECT * FROM unit');
        return new self($rows);
    }
}