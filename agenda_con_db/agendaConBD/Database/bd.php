<?php
    
    /**conexión a la base de datos */
class Databasee{

    
    private $host = "localhost";
    private $user  = "root";
    private $dbpass = "mysqlroot";
    public $conn;
    
    public function connection(){
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host" . $this->host, $this->user,$this->dbpass);

        }catch(PDOException $error) {
            echo "Can't connect: " . $error->getMessage();
            
        }
        return  $this->conn;
    }
}
?>