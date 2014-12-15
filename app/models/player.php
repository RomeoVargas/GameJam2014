<?php
class Player
{
    protected $client_id;

    public function getByClientId($client_id)
    {
        $player_id = $this->getPlayerId($client_id);
        $db = DB::conn();
        $player = $db->row('SELECT * FROM player WHERE id = ?', array($player_id));
        return new self($player);
    }

    protected function getPlayerId($client_id)
    {
        $db = DB::conn();
        $player_id = $db->value('SELECT player_id FROM player_id_finder WHERE client_id = ?', array($client_id));
        return $player_id;
    }

    public function getUnitStorage()
    {
        return new UnitStorage($this);
    }
}