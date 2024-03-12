<?php

class Db
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "predicciones2024";
    private $conn;
    private static $instance = null;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            throw new Error("Connection error: " . $this->conn->connect_error, 500);
        }

        $this->conn->set_charset("utf8");
        $this->conn->query("SET NAMES 'utf8'");
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function getResultArray($sql)
    {
        $data = [];
        $result = $this->conn->query($sql);

        if (!isset($result->num_rows)) return $data;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function getValue($sql)
    {
        $resultArray = $this->getResultArray($sql);
        if (count($resultArray) > 0) {
            return $resultArray[0];
        }

        return null;
    }
}
