<?php
    /*Comprobar que el 10º dígito corresponde al resto de dividir la suma de los otros
9 entre 9.*/
/* let nCuentaOk = true;
    let nCuenta = (e.target.value + "").split("").map((x) => Number(x));
    if (nCuenta.length === 10) {
        let cpNcuenta = [...nCuenta];
        cpNcuenta.pop();
        if (cpNcuenta.reduce((acu, valor) => acu + valor) % 9 === nCuenta[nCuenta.length - 1]) {
            nCuentaOk = true;
        } else {
            nCuentaOk = false
        }

    } else {
        nCuentaOk = false;
    }

    if (nCuentaOk) {
        document.forms[0].dni1.disabled = false;
        document.getElementById('enc').innerText = ' ';
        if (cValidos.find((v) => v == 'nCuenta') === undefined) {
            cValidos.push('nCuenta');
        }

    } else {
        cValidos = cValidos.filter((v) => v != 'nCuenta');
        console.log(cValidos);
        document.getElementById('enc').innerText = 'numero de cuenta incorrecto'
    } */
    function validarCuenta($nCuenta)
    {
        $cuentaOk = false;
        if (is_numeric($nCuenta)) {
            if (strlen($nCuenta)===10) {
                $arrayNCuenta  = array_map('intval', str_split($nCuenta));
                $sP9Nums=array_reduce(array_slice($arrayNCuenta, 0, 9), function ($accumulator, $item) {
                    return $accumulator+$item;
                });

                if ($sP9Nums%9===$arrayNCuenta[9]) {
                    $cuentaOk = true;
                }
            }
        }
        return $cuentaOk;
    }

    function validar_dni($dni)
    {
        $dniOk=false;
        if (strlen($dni)===9) {
            $letra = substr($dni, -1);
            $numeros = substr($dni, 0, -1);
            if (substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros%23, 1) == $letra && strlen($letra) == 1 && strlen($numeros) == 8) {
                $dniOk=true;
            }
        }

        return $dniOk;
    }
?>