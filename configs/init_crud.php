<?php

// session_start();
// mysqli_report(MYSQLI_REPORT_OFF);
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// define("HOST", ""); // The host you want to connect to.
// define("USER", ""); // The database username.
// define("PASSWORD", ""); // The database password.
// define("DATABASE", "bd_praticar4"); // The database name.

//---------------------------------------------------------------------------------------------

class DB_crud
{
    private static $host = "localhost";      // ou HOST, se estiver definido 
    private static $dbName = "bd_praticar4";                // substitua por DATABASE se quiser
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
