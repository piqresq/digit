<?php

include_once "product.php";

class Database
{
    public $host = "localhost";
    public $user = "root";
    public $pass = "";
    public $db = "digit_db";
    public $charset = "utf8mb4";
    public $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    public $pdo;

    public function getConnection()
    {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db;charset=$this->charset", $this->user, $this->pass, $this->options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
        Product::create_defaults($this);
        return $this->pdo;
    }
    public function executeQuery($query, $params = [])
    {
        $q = $this->pdo->prepare($query);
        if (empty($params))
            $q->execute();
        else {
            $q->execute($params);
        }
    }
    public function fetchQuery($query, $attr = null, $params = null)
    {
        $q = $this->pdo->prepare($query);
        if (empty($params))
            $q->execute();
        else
            $q->execute($params);
        if (is_null($attr))
            return $q->fetchAll();
        else
            return $q->fetchAll($attr);
    }
}
