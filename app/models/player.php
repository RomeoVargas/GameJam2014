<?php
class Player extends AppModel
{
    private $client_id;
    private $unit_storage;

    public function getByClientId($client_id)
    {
        $player_id = $this->getPlayerId($client_id);
        $db = DB::conn();
        $player = $db->row('SELECT * FROM player WHERE id = ?', array($player_id));
        $this->client_id = $client_id;
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
        if ($this->unit_storage) {
            return $this->unit_storage;
        }
        $this->unit_storage = new UnitStorage($this);
        return $this->unit_storage;
    }
}