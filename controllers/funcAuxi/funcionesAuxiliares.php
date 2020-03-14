<?php

function validarCuenta($nCuenta)
{
    $cuentaOk = false;
    if (is_numeric($nCuenta)) {
        if (strlen($nCuenta) === 10) {
            $arrayNCuenta  = array_map('intval', str_split($nCuenta));
            $sP9Nums = array_reduce(array_slice($arrayNCuenta, 0, 9), function ($accumulator, $item) {
                return $accumulator + $item;
            });

            if ($sP9Nums % 9 === $arrayNCuenta[9]) {
                $cuentaOk = true;
            }
        }
    }
    return $cuentaOk;
}

function validar_dni($dni)
{
    $dniOk = false;
    if (strlen($dni) === 9) {
        $letra = substr($dni, -1);
        $numeros = substr($dni, 0, -1);
        if (substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros % 23, 1) == $letra && strlen($letra) == 1 && strlen($numeros) == 8) {
            $dniOk = true;
        }
    }

    return $dniOk;
}

function validarFecha($fecha)
{
    $valores = explode('-', $fecha);
    if (count($valores) === 3 && checkdate($valores[1], $valores[2], $valores[0])) {
        return true;
    }
    return false;
}

/**
 *
 * Valida un email usando expresiones regulares. 
 *  Devuelve true si es correcto o false en caso contrario
 *
 * @param    string  $str la dirección a validar
 * @return   boolean
 *
 */
function validar_email($str)
{
    $matches = null;
    return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $str, $matches));
}

function validarTelefono($numero)
{
    $reg = "#^\(?\d{2}\)?[\s\.-]?\d{4}[\s\.-]?\d{4}$#";
    return (1 === preg_match($reg, $numero));
}
