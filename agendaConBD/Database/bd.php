<?php
    
    /**conexión a la base de datos */
class Databasee{

    
    private $host = "g1.ifc33b.cifpfbmoll.eu";
    private $user  = "ozaaj";
    private $dbpass = "abc123.";
    private $dbname = "agendadb";
    public $conn;
    
    public function connection(){
        $this->conn = null;

        try {
            $this->conn = new PDO("pgsql:host=" . $this->host . ";dbname=" . $this->dbname, $this->user,$this->dbpass);

        }catch(PDOException $error) {
            echo "Can't connect: " . $error->getMessage();
            
        }
        return  $this->conn;
    }
}
?>