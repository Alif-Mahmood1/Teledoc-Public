<?php
class Teledoc {
    public static function connect() {
        try {
            $db_name = "teledoc";
            $localhost = "localhost";
            $username = "root";
            $password = "";
            
            $port = 3306; // change this

            $con = new PDO("mysql:host=$localhost;dbname=$db_name;port=$port", $username, $password);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $con; // Return the PDO connection object
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            return null; 
        }
    }
}
?>
