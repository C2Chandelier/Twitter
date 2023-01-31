<?php

require_once(__DIR__ . '/DotEnv.php');

class MyPDO
{
    private $PDOInstance = null;
    private static $instance = null;
    private $host, $user, $pass, $database;
    public function __construct()
    {
        (new DotEnv(__DIR__ . '/.env'))->load();
        list($this->host, $this->user, $this->pass, $this->database) = [$_ENV['DATABASE_HOST'], $_ENV['DATABASE_USER'], $_ENV['DATABASE_PASSWORD'], $_ENV['DATABASE_NAME']];
        try {
            $this->PDOInstance = new PDO('mysql:dbname=' . $this->database . ';host=' . $this->host, $this->user, $this->pass);
            $this->PDOInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("ERROR: Could not connect. " . $e->getMessage());
        }
    }
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new MyPDO();
        }
        return self::$instance;
    }
    public function query($query)
    {
        return $this->PDOInstance->query($query);
    }
    public function prepare($query = '')
    {
        return $this->PDOInstance->prepare($query);
    }
    public function lastInsertId()
    {
        return $this->PDOInstance->lastInsertId();
    }
}

$myPDO = new myPDO();
