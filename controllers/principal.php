<?php 
    $controladores= array('clientes','cuentas','movimientos');
    if(isset($_REQUEST['cont'])){
        $controlador=filtrado($_REQUEST['cont']);
        if(in_array($_REQUEST['cont'],$controladores)){
            include("controllers/{$controlador}.php");
            echo 'noooooooooo';
        }else{
            echo 'wiiiiiii';
            include('views/principal.php');
        }
    }else{
        echo 'false';
        include('views/principal.php');
    }
?>