<?php

function link_db_crud()
{
    static $link_crud;

    $link_crud = new mysqli(HOST, USER, PASSWORD, DATABASE);

    if ($link_crud->connect_errno) {
        echo "Failed to connect to MySQL:" . $link_crud->connect_errno . " - " . $link_crud->connect_error;
    } else {
        $link_crud->set_charset('utf8');
        return $link_crud;
    }
}


// <?php

// class DB
// {
//     private static HOST;             // ou $host, se n estiver definido 
//     private static DATABASE;              // substitua por $dbName/$dataBase se quiser
//     private static USER;                     // ou $user/$username
//     private static PASSWORD;                     // ou $password
//     private static $conn = null;

//     public static function connect()
//     {
//         if (self::$conn === null) {
//             try {
//                 $dsn = "msqli:host=" . self::HOST . ";dbname=" . self::DATABASE . ";charset=utf8";
//                 self::$conn = new PDO($dsn, self::USER, self::PASSWORD);
//                 self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//             } catch (PDOException $e) {
//                 die("Erro na conexao: " . $e->getMessage());
//             }
//         }

//         return self::$conn;
//     }

//     public static function disconnect()
//     {
//         self::$conn = null;
//     }
// }