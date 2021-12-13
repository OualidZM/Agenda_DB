<?php

/**
 * Clase dónde estara toda la  lógica
 */
class FetchData{
    private $conn;
    public $name_column = 'name';
    public $phone_column = 'phone_number';
    
    public function __construct($db){
        $this->conn = $db;
    }

    /**
     * Comprueba si existe la base de datos, si no existe la creara
     */
    function checkDB(){
        $qrdb = "SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.SCHEMATA 
                WHERE SCHEMA_NAME = 'agendaDB'";
        $data = $this->conn->prepare($qrdb);
        $data->execute();
        $numdb = $data->fetchAll();
        foreach($numdb as $k=>$v){
            $key_value_db = $v[$k];

            if($key_value_db == 1){
    
            }else {
                $qr_create_db = "CREATE DATABASE agendadb";
                $qr_ex_db = $this->conn->prepare($qr_create_db);
                $qr_ex_db->execute();
            }
        }
    }
    
    /**
     * Comprueba si existe la tabla, si no existe la creara
     */
    function checkTable() {
        $qrtb = "SHOW TABLES LIKE 'contacts'";
        $table_check_qr = $this->conn->prepare($qrtb);
        $table_check_qr->execute();
        $numtb = $table_check_qr->fetchAll();

            if(count($numtb) == 1){
            }else {
                $qr_create_tb =  "USE agendaDB; " . 
                                "CREATE TABLE IF NOT EXISTS contacts (
                                    contact_id INT AUTO_INCREMENT PRIMARY KEY,
                                    name VARCHAR(255) NOT NULL,
                                    phone_number VARCHAR(255) NOT NULL
                                    )";
                $qr_ex_tb = $this->conn->prepare($qr_create_tb);
                $qr_ex_tb->execute();
            }
        }

        /**Esta funcion se encarga de obtener todo los datos i meter-los en un array asociativo */
    function getData(&$result) {
        $qr_check_name = "SELECT name,phone_number
        FROM contacts";
        $check_qr_ex = $this->conn->prepare($qr_check_name);
        $check_qr_ex->execute();
        $result = $check_qr_ex->fetchAll();
        $tt = array_unique($result, SORT_REGULAR);

        $column_key = 'phone_number';
        $index_key = 'name';
        $first_names =array_column($tt, $column_key, $index_key);
        $result = $first_names;
        return $result;
    }
/**
 * Esta funcion permite añadir valores a la db
 */
    function add($k,$v){

        try{
        $add_qr = "USE agendadb; " .
                    "INSERT INTO contacts(name,phone_number)
        VALUES(:name_value,:phone_value)";
        $add_ex_qr = $this->conn->prepare($add_qr);

        $add_ex_qr->bindParam(':name_value',$k);
        $add_ex_qr->bindParam(':phone_value',$v);
        $add_ex_qr->execute();  

        }catch( PDOException $excepiton ) {
            echo "Connection error :" . $excepiton->getMessage();
        }
    }

    /**
     * permite hacer update a la db
     */
    function update($k,$v){
        $upd_qr = "USE agendadb; " .
                "UPDATE contacts 
                SET 
                    phone_number = :phone_value
                WHERE
                    name = :name_value";
        $upd_ex_qr = $this->conn->prepare($upd_qr);
        $upd_ex_qr->bindParam(':name_value',$k);
        $upd_ex_qr->bindParam(':phone_value',$v);
        $upd_ex_qr->execute();  
        
    }

    /**
     * permite eliminar en db
     */
    function delete($k){
        $upd_qr = "USE agendadb; " .
        "DELETE FROM contacts 
            WHERE
                name = :name_value";
        $upd_ex_qr = $this->conn->prepare($upd_qr);
        $upd_ex_qr->bindParam(':name_value',$k);
        $upd_ex_qr->execute();  
    }
    
    /**
     * muestra los datos
     * 
     */
    function showData($arr){
        $len_arr = count($arr);
        if($len_arr == 0){
            echo "Agenda Vacia";
        }else{
            echo "Contactos: <br>";
            echo "<ol>";
            foreach($arr as $k => $v){
                echo "<li>" . $k . ":" . $v . "</li>";
            }
                echo "</ol>";
        }
    }

    /**
     * elinimna todo
     */
    
    function deleteAll(){
        $upd_qr = "truncate contacts";
        $upd_ex_qr = $this->conn->prepare($upd_qr);
        $upd_ex_qr->execute();  
    }
}

?>
