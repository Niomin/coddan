<?php

class World
{
    public function __construct()
    {
        $this->db = Db::getInstance();
        $this->loader = new Loader();
    }

    private function init()
    {
        if (!$this->checkDb()) {
            $this->loader->load();
        }
    }

    private function checkDb()
    {
        $tables = $this->db->fetchColumn('show tables');
        $checkTables = ['City', 'Country', 'CountryLanguage'];
        foreach ($checkTables as $table) {
            if (!in_array($table, $tables)) {
                return false;
            }
        }
        return true;
    }

    private function getSql($orderBy = '', $type = '')
    {
        $sql = <<<sql
SELECT
  cast(c.Continent AS CHAR) Continent,
  c.Region,
  count(c.Name)             Countries,
  AVG(c.LifeExpectancy)     LifeDuration,
  SUM(c.Population)         Population,
  SUM(ci.CityCount)         Cities,
  SUM(l.LanguageCount)      Languages
FROM Country c
  LEFT JOIN (SELECT
               COUNT(c.ID) CityCount,
               c.CountryCode
             FROM City c
             GROUP BY c.CountryCode) ci ON ci.CountryCode = c.Code
  LEFT JOIN (SELECT
               COUNT(l.Language) LanguageCount,
               l.CountryCode
             FROM CountryLanguage l
             GROUP BY l.CountryCode) l ON l.CountryCode = c.Code
GROUP BY c.Continent, c.Region
sql;

        $enableFields = ['Continent', 'Region', 'Countries', 'LifeDuration', 'Population', 'Cities', 'Languages'];

        if ($orderBy && in_array($orderBy, $enableFields) && in_array($type, ['ASC', 'DESC'])) {
            $sql .= " ORDER BY $orderBy $type";
        }
        return $sql;
    }

    public function load($orderBy = 'Continent', $type = 'ASC')
    {
        $this->init();

        return $this->db->fetchAll($this->getSql($orderBy, $type));
    }
}