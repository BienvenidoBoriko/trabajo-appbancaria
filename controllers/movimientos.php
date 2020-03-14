<?php
include("models/BancoMovimientos.php");
include("models/BancoCuentas.php");
include('controllers/funcAuxi/funcionesAuxiliares.php');

$cuentas = new BancoCuentas();
$movimientos = new BancoMovimientos();
$errores = [];
if ((isset($_REQUEST["cont"]) && $_REQUEST["cont"] == 'movimientos')) {
    if (isset($_POST["dato"])) {
        $datos = json_decode($_POST["dato"], true);
        $opr = filtrado($datos['opr']);
        switch ($opr) {
            case 1: //listar movimientos
                if (isset($datos["nCuenta"])) {
                    $nCuenta = filtrado($datos["nCuenta"]);
                    if (validarCuenta($nCuenta)) {
                        if ($cuentas->verificaCuenta($nCuenta) !== true) {
                            array_push($errores, "Error la cuenta no existe");
                        }
                    } else {
                        array_push($errores, "Error numero de cuenta invalido");
                    }
                } else {
                    array_push($errores, "Error numero de cuenta invalido");
                }

                if (isset($datos["fechaP"])) {
                    $fechaP = filtrado($datos["fechaP"]);
                    if (!validarFecha($fechaP)) {
                        array_push($errores, "Error fecha1 mal formada");
                    }
                } else {
                    array_push($errores, "Error en fecha1");
                }

                if (isset($datos["fechaU"])) {
                    $fechaU = filtrado($datos["fechaU"]);
                    if (validarFecha($fechaU)) {
                        $fecha1 = new DateTime($fechaP);
                        $fecha2 = new DateTime($fechaU);
                        if ($fecha1 > $fecha2) {
                            array_push($errores, "Error fecha2 es menor que fecha1");
                        }
                    } else {
                        array_push($errores, "Error fecha2 mal formada");
                    }
                } else {
                    array_push($errores, "Error fecha2 incorrecta");
                }

                if (count($errores) > 0) {
                    echo json_encode($errores);
                } else {
                    if ($datos = $movimientos->getMovimientos($nCuenta, $fechaP, $fechaU)) {
                        echo json_encode($datos);
                    }else {
                        echo json_encode(array('mensaje'=>'error no exiten movimientos'));
                    }
                }

                break;
            case 2://eliminar movimientos
                if (isset($datos["nCuenta"])) {
                    $nCuenta = filtrado($datos["nCuenta"]);
                  if($movimientos->eliminarMovimientos($nCuenta)){
                    echo json_encode(array('el_mov'=>true));
                  }else {
                    $movimientos->getDb()->rollback();
                    echo json_encode(array('el_mov'=>false));
                  }

                } else {
                    echo json_encode(array('el_mov'=>-1));
                } 
                break;
        }
    } else {
        include('views/l_movimientos.php');
    }
} else {
    header("Location: index1.php?mensaje=hubo un error al recibir los datos");
}
