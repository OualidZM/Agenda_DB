<?php

/**
 * En esta clase, es dónde se encuentra la lógica de la agenda
 */
class FetchData{
    
    private $conn;
    /**
    * Creamos un constructor, que tendrá como parámetro la conexión a la Base dee Datos, esta conexión la obtendremos en agenda.php
     */
    public function __construct($db){
        $this->conn = $db;
    }


    /**
    * Esta funcion se encarga de obtener todos los datos y meter-los en un array asociativo, como parámetro le pasaremos el
    * array asociativo que habíamos definido en agenda.php, pero este lo pasaremos por referencia, para que
    * cuando obtenga los contactos desde la db, pueda modificar el array original y estén disponibles en agenda.php
    */
    function getData(&$result) {
        $qr_check_name = "SELECT name,phone_number
        FROM contacts";
        $check_qr_ex = $this->conn->prepare($qr_check_name);
        $check_qr_ex->execute();
        $result = $check_qr_ex->fetchAll();


        // $contacts_arr = array_unique($result, SORT_REGULAR);
        /**
        * al obtener los datos desde postgres, el resultado que obtenemos es array de arrays,
        * para poder simplificar-lo y obtener clave-valor, utilizamos un metodo que se llama 'array_colunm'
        * que el primer parámetro es el array, el segundo parámetro es el valor para el array asociativo
        * y finalmente el tercer valor, es la clave para el array asociativo
        */
        $column_key = 'phone_number';
        $index_key = 'name';
        $first_names =array_column($result, $column_key, $index_key);
        $result = $first_names;
        return $result;
    }
/**
 * Esta funcion permite añadir valores a la DB
 */
    function add($k,$v){

        try{
        $add_qr = 
                "INSERT INTO contacts(name,phone_number)
                    VALUES(:name_value,:phone_value)";
        $add_ex_qr = $this->conn->prepare($add_qr);

        /**
        * En la query de arriba podemos observar que está el ':name_value' y el ':phone_value', estos no tienen ningún
        * valor en sí, para poder especificar-le un valor utilizamos el bindParam, que básicamente le indicamos cual será
        * su valor
        */
        $add_ex_qr->bindParam(':name_value',$k);
        $add_ex_qr->bindParam(':phone_value',$v);
        $add_ex_qr->execute();  

        }catch( PDOException $excepiton ) {
            echo "Connection error :" . $excepiton->getMessage();
        }
    }

    /**
     * Esta funcion permite hacer el update a los contactos
     */
    function update($k,$v){
        $upd_qr =
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
     * Esta funcion permite eliminar contactos
     */
    function delete($k){
        $upd_qr =
                "DELETE FROM contacts 
                 WHERE
                    name = :name_value";
        $upd_ex_qr = $this->conn->prepare($upd_qr);
        $upd_ex_qr->bindParam(':name_value',$k);
        $upd_ex_qr->execute();  
    }
    
    /**
     * Esta funcion muestra los contactos que hay en la Base de datos
     * 
     */
    function showData($arr){
    /**
    * '$arr', hace referencia al array asociativo, cont todos los contactos que hay en la base de datos
    * comprueba que la base de datos no este vacío, sino esta vacío, con un foreach puedo mostrar el nombre(clave),
    * y el teléfono móvil(valor)
    */
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
    * Esta funcion permite eliminar todo los contactos que hay en la base de datos
    */
    
    function deleteAll(){
        $upd_qr = "truncate contacts";
        $upd_ex_qr = $this->conn->prepare($upd_qr);
        $upd_ex_qr->execute();  
    }
}

?>
