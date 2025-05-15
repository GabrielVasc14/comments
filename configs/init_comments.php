<?php

class DB
{
    private static $host = "localhost";      // ou HOST, se estiver definido 
    private static $dbName = "bd_comments";                // substitua por DATABASE se quiser
    private static $username = "root";                      // ou USER
    private static $password = "";                      // ou PASSWORD
    private static $conn = null;

    public static function connect()
    {
        if (self::$conn === null) {
            try {
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbName . ";charset=utf8";
                self::$conn = new PDO($dsn, self::$username, self::$password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro na conexao: " . $e->getMessage());
            }
        }

        return self::$conn;
    }

    public static function disconnect()
    {
        self::$conn = null;
    }
}
