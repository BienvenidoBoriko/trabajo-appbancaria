<?php
$controladores = array('clientes', 'cuentas', 'movimientos');
if (isset($_REQUEST['cont'])) {
    $controlador = filtrado($_REQUEST['cont']);
    if (in_array($_REQUEST['cont'], $controladores)) {

        include "controllers/{$controlador}.php";
    } else {
        include 'views/principal.php';
    }
} else if (isset($_REQUEST['vista'])) {
    $vistas = array('principal', 'l_movimientos', 'ingresos_reintegros', 'cierreCuentas', 'aper_cuentas');
    $vista = filtrado($_REQUEST['vista']);
    if (in_array($vista, $vistas)) {
        include "views/{$vista}.php";
    } else {
        include 'views/principal.php';
    }
} else {
    include 'views/principal.php';
}
?>