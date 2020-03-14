<?php
include("models/BancoCuentas.php");
include('controllers/funcAuxi/funcionesAuxiliares.php');

$cuentas = new BancoCuentas();
$errores = [];
if ((isset($_REQUEST["cont"]) && $_REQUEST["cont"] == 'cuentas')) {
    if (isset($_POST['dato'])) {
        $datos = json_decode($_POST["dato"], true);
        $opr = filtrado($datos['opr']);
        switch ($opr) {
            case 1: //operacion de grabar registro
                if (isset($datos["nCuenta"])) {
                    $nCuenta = filtrado($datos["nCuenta"]);
                    if (validarCuenta($nCuenta)) {
                        if ($cuentas->verificaCuenta($nCuenta) === true) {
                            array_push($errores, "Error la cuenta ya esta dada de alta");
                        }
                    } else {
                        array_push($errores, "Error numero de cuenta invalido");
                    }
                } else {
                    array_push($errores, "Error numero de cuenta invalido");
                }

                if (isset($datos["dni1"])) {
                    $dni1 = filtrado($datos["dni1"]);
                    if (!validar_dni($dni1)) {
                        array_push($errores, "Error dni1 incorrecto");
                    }
                } else {
                    array_push($errores, "Error dni1 incorrecto");
                }

                if (isset($datos["dni2"])) {
                    $dni2 = filtrado($datos["dni2"]);
                    if (!validar_dni($dni2)) {
                        array_push($errores, "Error dni2 incorrecto");
                    } else if (strcmp($dni1, $dni2) === 0) {
                        array_push($errores, "Error dni2 es igual a dni1");
                    }
                } else {
                    array_push($errores, "Error dni2 incorrecto");
                }

                if (isset($datos["importe"])) {
                    $importe = filtrado($datos["importe"]);
                    if (empty($importe)) {
                        array_push($errores, "Error el importe no puede estar vacio");
                    } else if (is_numeric($importe)) {
                        $importe = intval($importe);
                        if ($importe < 1) {
                            array_push($errores, "Error el importe no puede ser menor de 1");
                        }
                    }
                } else {
                    array_push($errores, "Error importe no recibido");
                }

                if (count($errores) > 0) {
                    echo json_encode(array('cuenta' => $errores));
                } else {
                    if ($cuentas->grabarRegistro($nCuenta, $dni1, $dni2, $importe) === true) {
                        echo json_encode(array('cuenta' => true));
                    }else{
                        echo json_encode(array('cuenta' => false));
                    }
                }
                break;
            case 2: //cierre de cuentas
                if (isset($datos["nCuenta"])) {
                    $cuentas->getDb()->autocommit(false);
                    $nCuenta = filtrado($datos["nCuenta"]);
                    if ($cuentas->cierreCuenta($nCuenta)) {
                        echo json_encode(array('cierre' => true));
                    } else {
                        echo json_encode(array('cierre' => false));
                    }
                } else {
                    echo json_encode(array('cierre' => 'Error con la cuenta'));
                }
                break;
            case 3: //verificar cuenta
                if (isset($datos["nCuenta"])) {
                    $nCuenta = filtrado($datos["nCuenta"]);
                    if (validarCuenta($nCuenta)) {
                        echo json_encode(array('cuenta' => true));
                    } else {
                        echo json_encode(array('cuenta' => false));
                    }
                } else {
                    echo json_encode(array('cuenta' => -1));
                }
                break;
            case 4: //obtener saldo de cuenta
                if (isset($datos["nCuenta"])) {
                    $nCuenta = filtrado($datos["nCuenta"]);
                    $saldo = $cuentas->getSaldo($nCuenta);
                    echo json_encode(array('saldo' => $saldo));
                } else {
                    echo json_encode(array('saldo' => false));
                }
                break;
            case 5: //obtener titulares de cuenta
                if (isset($datos["nCuenta"])) {
                    $nCuenta = filtrado($datos["nCuenta"]);
                    $titulares = $cuentas->getClientes($nCuenta);
                    if ($titulares !== false) {
                        echo json_encode(array('titulares' => $titulares));
                    }
                } else {
                    echo json_encode(array('titulares' => 'error'));
                }
                break;
            case 6: //obtener datos de cuenta
                if (isset($datos["nCuenta"])) {
                    $nCuenta = filtrado($datos["nCuenta"]);
                    $datos = $cuentas->getDatosCuenta($nCuenta);
                    if ($datos !== false) {
                        echo json_encode(array('datos' => $datos));
                    }else{
                        echo json_encode(array('datos' => false));
                    }
                } else {
                    echo json_encode(array('datos' => -1));
                }
                break;
        }
    } else {
        include('views/aper_cuentas.php');
    }
} else {
    header("Location: index1.php?mensaje=hubo un error al recibir los datos");
}
