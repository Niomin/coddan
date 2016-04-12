<?php

class Db
{
    /**
     * @var $this
     */
    private static $instance;

    /**
     * @var PDO
     */
    private $pdo;

    private function __construct()
    {
        $connect = sprintf('%s:host=%s;dbname=%s', Config::getDbType(), Config::getDbHost(), Config::getDbName());
        $this->pdo = new PDO($connect, Config::getDbLogin(), Config::getDbPass());
    }

    private function __clone() {}

    private function __wakeup() {}

    /**
     * @return $this
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * @param $sql
     * @return int
     */
    public function exec($sql)
    {
        return $this->pdo->exec($sql);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     */
    public function query($sql, &$params = [])
    {
        $query = $this->pdo->query($sql);

        foreach ($params as $key => $param) {
            $query->bindParam($key, $params[$key]);
        }

        $query->execute();

        return $query;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function fetchAll($sql, &$params = [])
    {
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchColumn($sql, &$params = [])
    {
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_COLUMN);
    }
}