<?php

class Plants extends mysqli
{
    private $db;

    public function __construct()
    {
        $this->db = Db::getInstance();
    }

    public function getListOfPlants()
    {
        $query = "SELECT * FROM plants";
        $result = $this->db->getResultArray($query);
        return $result;
    }

    public function getSinglePlant($id)
    {
        $query = "SELECT * FROM plants WHERE id = " . $id . " LIMIT 1";
        $result = $this->db->getValue($query);
        return $result;
    }
}
