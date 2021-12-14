<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./lib/css/style.css">
</head>
<body >
    <?php

    /**
    * Lo primero de todo importo la conexión para la base de datos 'db.php' y también importo toda
    * la lógica 'fetch_data.php'
     */
        include_once "./Database/bd.php";
        include_once "./Service/fetch_data.php";

    /**
    * Ahora que ya están importados, lo que hago es instanciar la clase 'Databasee' obtener el metodo 'connection()',
    *este metodo será el que nos permitirá obtener la conexion con la db, seguidamente instanciamos
    * la clase 'FetchData' y a este objecto le pasamos como parámetro la conexión a la db
    *
    */

    $db = new Databasee();
    $connn = $db->connection();
    $get_data = new FetchData($connn);

    /**
    * Creamos un array asociativo, la key será el nombre y el valor será el número de teléfono
    */
    $name_arr_values = [];

    /**
    * este método, obtendrá los datos existentes en la base de datos, como parámetro le pasamos un
    * array, (el array asociativo que declaramos arriba), ya que la información que se recibirá desde postrgres,
    * se almacenara en este
    */
    $get_data->getData($name_arr_values);


    /**
     * comprobamos si le ha dado al submit
     */
        if(isset($_GET['submit'])){


    /**
    * Obtenemos el nombre y el número móvil, que el usuario introdujo
    */
            $name = $_GET['name'];
            $phone = $_GET['phone'];
 
            
            /**
             * si el nombre está vacío, entonces retornara un error
             */
            if(empty($name)){
                echo "<p class=" . "redd" . "> You need to add a Name </p>";
                header('Refresh: 2; URL=./agenda.php');
            }

    /**
    * si el usuario no introdujo el número móvil, entonces se eliminara de la
    * base de datos, pero antes de que se elimine, se comprobara que exista,
    * esto lo podemos hacer con el método 'array_key_exists' para comprovar si existe la key que se está buscando
    * si no existe retorna un error
    */
            elseif(empty($phone)){ //delete
                if(array_key_exists($name,$name_arr_values)){ //check if exists in db
                    $get_data->delete($name);
                    echo "<p class=" . "del" . "> Se elimino el contacto '$name' </p>";

                }else{ //if doesn't exists show this message
                    echo "<p class=" . "del" . "> El contacto '$name' no existe en la agenda </p>";
                    
                }
                header('Refresh: 2; URL=./agenda.php');

            }
    
    /**
    * Si el usuario introdujo el nombre y el número móvil, esto significa o bien
    * que quiere añadir un contacto más a la base de datos, o es que quiere hacer
    * un, upate al contacto, para esto utilizamos de nuevo el método 'array_key_exists'
    * para saber si existe la key en el array asociativo, si existe se hace un, upate
    * si no, se añade
    */
                elseif(array_key_exists($name,$name_arr_values) && !empty($phone)){//update
                $get_data->update($name,$phone);
                echo "<p class=" . "update" . "> El valor de '$name', ahora es: $phone </p>";
                header('Refresh: 2; URL=./agenda.php');

            }

            else{

                $get_data->add($name,$phone);


                echo "<p class=" . "succes" . "> Se añadio '$name' con valor: '$phone' </p>";
                header('Refresh: 2; URL=./agenda.php');
            }
        }

    /**
    * Si el usuario clica el botón 'Eliminar todos los contactos', entonces lo primero que se hace
    * es comprobar que el array asociativo no este vacío, esto lo hacemos con él con el count(),
    * si el count no es igual a 0 entonces se eliminan todos, en cambio, si
    * es 0, se muestra un mensaje de que no hay contactos
    */
        if(isset($_GET['destroy'])){
            if(count($name_arr_values) == 0){
                echo "<p class=" . "nothingToDelete" . "> La agenda ya esta vacia </p>";
            }else{
                $get_data->deleteAll();
                echo "<p class=" . "alldeleted" . "> Todos los contactos se eliminaron! </p>";
            }
            header('Refresh: 2; URL=./agenda.php');

        }

    ?>
    <h1 class="title">Agenda App</h1>

    <form method="GET" class="formStyle">

            <input type="text" name="name" placeholder="Name" />
            <input type="text" name="phone" placeholder="Phone Number" />
            <input type="submit" name="submit" value="Insertar"><br/>
            <button name="destroy" class="destroybtn">Delete all Contacts</button>
    </form>
</html>
<div class="agendaStyle">

<?php
    /**
    * Para poder mostrar los contactos que existen en el array, utilizamos el metodo showData, que está en la clase 'FetchData'
    */
$get_data->showData($name_arr_values);

?>
</div>

</body>
