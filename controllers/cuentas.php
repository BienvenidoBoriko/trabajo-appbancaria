<?php
include("models/BancoCuentas.php");
include('controllers/funcAuxi/funcionesAuxiliares.php');

$cuentas = new BancoCuentas();
$errores = [];
if ((isset($_REQUEST["cont"]) && $_REQUEST["cont"] == 'cuentas')) {
    if (isset($_POST['opr'])) {
        $operacion = filtrado($_POST['opr']);
        switch ($opr) {
            case 1: //operacion de grabar registro
                if (isset($_POST["nCuenta"])) {
                    $nCuenta = filtrado($_POST["nCuenta"]);
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

                if (isset($_POST["dni1"])) {
                    $dni1 = filtrado($_POST["dni1"]);
                    if (!validar_dni($dni1)) {
                        array_push($errores, "Error dni1 incorrecto");
                    }
                } else {
                    array_push($errores, "Error dni1 incorrecto");
                }

                if (isset($_POST["dni2"])) {
                    $dni2 = filtrado($_POST["dni2"]);
                    if (!validar_dni($dni2)) {
                        array_push($errores, "Error dni2 incorrecto");
                    } else if (strcmp($dn1, $dni2) !== 0) {
                        array_push($errores, "Error dni2 es igual a dni1");
                    }
                } else {
                    array_push($errores, "Error dni2 incorrecto");
                }

                if (isset($_POST["importe"])) {
                    $importe = filtrado($_POST["importe"]);
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
                    $mensaje = implode('\n', $errores);
                    header("Location: index1.php?mensaje=$mensaje");
                } else {
                    if ($cuentas->grabarRegistro($nCuenta, $dni1, $dni2, $importe) === true) {
                        header("Location: index1.php?mensaje= registros insertados con exito");
                    }
                }
                break;
            case 2: //
                break;
        }
    }else{
        include('views/aper_cuentas.php');
    }
} else {
    header("Location: index1.php?mensaje=hubo un error al recibir los datos");
}
