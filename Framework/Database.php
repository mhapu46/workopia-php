<?php

namespace Framework;

use PDO;
class Database{
    public $conn;

    /**
     * Constructor for Database class
     *
     * @param array $config
     *
     * @throws Exception
     */
    public function __construct($config){
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
        }
        catch (PDOException $e) {
            throw new Exception("Database Connection Failed: {$e->getMessage()}");
        }
    }

    /**
     * Query the dataase
     *
     * @param string $query
     * @return PDOStatement
     * @throws PDOException
     */
    public function query($query, $params = []){
        try {
            $sth = $this->conn->prepare($query);

            //Bind named params
            foreach ($params as $param => $value) {
                $sth-> bindValue(':'.$param, $value, PDO::PARAM_STR);
            }
            $sth->execute();
            return $sth;
        }
        catch (PDOException $e) {
            throw new Exception("Database Query Failed: {$e->getMessage()}");
        }
    }
}