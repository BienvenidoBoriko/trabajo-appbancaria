<?php 
    require_once("models/*");
    $controladores= array('clientes','cuentas','movimientos');
    if(isset($_GET['cont'])){
        $controlador=filtrado($_GET['cont']);
        if(in_array($_GET['cont'],$controladores)){
            include("controllers/{$controlador}.php");
        }else{
            include('views/principal.php');
        }
    }else{
        include('views/principal.php');
    }
?>