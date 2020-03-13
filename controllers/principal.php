<?php 
    $controladores= array('clientes','cuentas','movimientos');
    if(isset($_REQUEST['cont'])){
        $controlador=filtrado($_REQUEST['cont']);
        if(in_array($_REQUEST['cont'],$controladores)){
            include("controllers/{$controlador}.php");
        }else{
            include('views/principal.php');
        }
    }else{
        include('views/principal.php');
    }
?>