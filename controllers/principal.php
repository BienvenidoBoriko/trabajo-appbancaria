<?php 
    include("models/*");
    $e =new BancoCuentas();
    $controladores= array('clientes','cuentas','movimientos');
    if(isset($_POST['cont'])){
        $controlador=filtrado($_POST['cont']);
        if(in_array($_POST['cont'],$controladores)){
            include("controllers/{$controlador}.php");
        }else{
            include('views/principal.php');
        }
    }else{
        echo 'false';
        include('views/principal.php');
    }
?>