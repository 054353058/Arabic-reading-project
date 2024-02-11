<?php 

$host = "localhost"; 
$dbname = "project1"; 
$user = "root";
$pass = '';

$dsn = "mysql:host=$host;dbname=$dbname";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {

    $con = new PDO($dsn, $user, $pass, $options);

    function howMany($table) {
        global $con;
        $sql = $con->prepare("SELECT * FROM $table");
        $sql->execute();
        $count = $sql->rowCount();

        return $count;
    }

    function checer($table) {
        global $con;
        $sql = $con->prepare("SELECT * FROM $table");
        $sql->execute();
        $count = $sql->rowCount();

        return $count;
    }
}catch(PDOException $e) {
    die("Connection faild" . $e->getMessage());
}

?>