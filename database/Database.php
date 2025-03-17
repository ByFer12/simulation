<?php

namespace Database;
class Database{

    private static $instance = null;
    private $pdo;
    private $config;

    private function __construct()
    {
        $this->config = require_once __DIR__ . '/../config/db.php';
        try {
            $dsn="mysql:host={$this->config['host']};dbname={$this->config['dbname']};charset={$this->config['charset']}";
            $this->pdo = new \PDO($dsn, $this->config['user'], $this->config['password'], $this->config['options']);

        } catch (\Throwable $th) {
            throw new \PDOException("No se pudo conectar a la base de datos: ".$th->getMessage());
        }
    }


    public static function getInstance(): Database{

        if(self::$instance == null){
            self::$instance = new Database();

        }

        return self::$instance;
    }

    public function getConnection(): \PDO{
        return $this->pdo;
    }


    
}

?>