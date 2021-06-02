<?php

class Database{
    public $host = "sql113.epizy.com";
    public $user = "epiz_28776373";
    public $pass = "ED1Rn6rYiPT4xF";
    public $db = "epiz_28776373_digit";
    public $charset = "utf8mb4";
    public $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
    public $pdo;

    public function getConnection(){
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db;charset=$this->charset", $this->user, $this->pass, $this->options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
        return $this->pdo;
    }
    public function executeQuery($query, $attr=null, ...$params){
        $q=$this->pdo->prepare($query);
        if(is_null($params))
            $q->execute();
        else 
            $q->execute($params);
        if(is_null($attr))
            return $q->fetchAll();
        else 
            return $q->fetchAll($attr);
    }
}

?>