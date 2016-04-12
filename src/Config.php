<?php

class Config
{
    private static $dbHost = 'localhost';

    private static $dbLogin = 'login';

    private static $dbName = 'coddan';

    private static $dbPass = 'password';

    private static $dbType = 'mysql';

    private static $filePath = __DIR__ . '/world.sql.gz';

    public static function getDbHost()
    {
        return self::$dbHost;
    }

    public static function getDbLogin()
    {
        return self::$dbLogin;
    }

    public static function getDbName()
    {
        return self::$dbName;
    }

    public static function getDbPass()
    {
        return self::$dbPass;
    }

    public static function getDbType()
    {
        return self::$dbType;
    }

    public static function getFilePath()
    {
        return self::$filePath;
    }
}