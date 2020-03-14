<?php
include("models/BancoClientes.php");
include('controllers/funcAuxi/funcionesAuxiliares.php');

$clientes = new BancoClientes();
$errores = [];
if ((isset($_REQUEST["cont"]) && $_REQUEST["cont"] == 'movimientos')) {
    if (isset($_POST["dato"])) {
        $datos = json_decode($_POST["dato"], true);
        $opr = filtrado($datos['opr']);
        switch ($opr) {
            case 1: //eliminar clientes
                if (isset($datos["dni"])) {
                    $dni = filtrado($datos["dni"]);
                    if ($clientes->getNumCuenta($dni) == 1) {
                        if ($clientes->eliminarClientes($dni)) {
                            $clientes->getDb()->commit();
                            $clientes->getDb()->autocommit(true);
                            $oprOk = true;
                        } else {
                            $oprOk = false;
                            $clientes->getDb()->rollback();
                            $clientes->getDb()->autocommit(true);
                        }
                    } else if ($clientes->getNumCuenta($dni) > 1) {
                        if ($clientes->modificarNumCuentas($dni)) {
                            $oprOk = true;
                            $clientes->getDb()->commit();
                            $clientes->getDb()->autocommit(true);
                        } else {
                            $oprOk = false;
                            $clientes->getDb()->rollback();
                            $clientes->getDb()->autocommit(true);
                        }
                    }
                    if ($oprOk) {
                        echo json_encode(array('el_client' => true));
                    } else {
                        $movimientos->getDb()->rollback();
                        echo json_encode(array('el_client' => false));
                    }
                } else {
                    echo json_encode(array('el_client' => -1));
                }
                break;
            case 2://obtener datos de los clientes
                if (isset($datos["dni"])) {
                    $dni = filtrado($datos["dni"]);
                    $datos=$clientes->getDatosCliente($dni);
                    if($datos===false){
                        echo json_encode(array('datos' => false));
                    }else if($datos===-2){
                        echo json_encode(array('datos' => -1));
                    }else {
                        echo json_encode(array('datos' => $datos));
                    }

                } else {
                    echo json_encode(array('el_client' => -1));
                }
            break;
        }
    } else {
        include('views/cierreCuentas.php');
    }
} else {
    header("Location: index1.php?mensaje=hubo un error al recibir los datos");
}
