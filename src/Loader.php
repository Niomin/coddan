<?php

class Loader
{
    public function __construct()
    {
        $this->filePath = Config::getFilePath();
        $this->db = Db::getInstance();
    }

    public function load()
    {
        $this->db->beginTransaction();
        $sql = $this->readFile();
        $this->db->exec($sql);
        $this->db->commit();
    }

    private function readFile()
    {
        return gzdecode(file_get_contents($this->filePath));
    }
}