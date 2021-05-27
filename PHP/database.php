<?php

class Database{
    public $host = "localhost";
    public $user = "root";
    public $pass = "";
    public $db = "digit";
    public $pdo;

    function getConnection(){
        try{
        $pdo = new PDO("mysql:host={$this->host};db={$this->db}", $this->user,'');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch(PDOexception $exception){
            echo "<p>Connection Error: $exception->getMessage()</p>";
        }
        return $pdo;
    }
    function fetchColumns(){
        if($this->pdo){
            $query=$this->pdo->query("DESC products");
            return $query->fetchAll(PDO::FETCH_COLUMN);
        }
        return false;
    }
}

?>