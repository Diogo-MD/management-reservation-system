<?php

class Database {
    private static $instance = null;
    
    public static function getInstance() {
        if(self::$instance === null) {
            $host = 'localhost';
            $dbname = 'sistema_reserva';
            $username = 'root';
            $password = 'Osasco@2018';

            self::$instance = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        
        return self::$instance;
    }
}

?>
