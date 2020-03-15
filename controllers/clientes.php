<?php
include("models/BancoClientes.php");
include('controllers/funcAuxi/funcionesAuxiliares.php');

$clientes = new BancoClientes();
$errores = [];

if ((isset($_REQUEST["cont"]) && $_REQUEST["cont"] == 'clientes')) {
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
            case 2: //obtener datos de los clientes
                if (isset($datos["dni"])) {
                    $dni = filtrado($datos["dni"]);
                    $datos = $clientes->getDatosCliente($dni);
                    if ($datos === false) {
                        echo json_encode(array('datos' => false));
                    } else if ($datos === -2) {
                        echo json_encode(array('datos' => -1));
                    } else {
                        echo json_encode(array('datos' => $datos));
                    }
                } else {
                    echo json_encode(array('el_client' => -1));
                }
                break;
            case 3: //verificar usuario

                if (isset($datos["dni"])) {
                    $dni = filtrado($datos["dni"]);
                    $datos = $clientes->verificaCliente($dni);
                    if ($datos === false) {
                        echo json_encode(array('cliente' => false));
                    } else if (($datos === true)) {
                        echo json_encode(array('cliente' => true));
                    }
                } else {
                    echo json_encode(array('cliente' => -1));
                }
                break;
            case 4: //registrar cliente
                if (isset($datos["nombre"])) {
                    $nombre = filtrado($datos["nombre"]);
                    if (strlen($nombre)<5) {
                        array_push($errores, "Error nombre long menor de 5");
                    } 
                } else {
                    array_push($errores, "Error en nombre");
                }

                if (isset($datos["dni"])) {
                    $dni = filtrado($datos["dni"]);
                    if (!validar_dni($dni)) {
                        array_push($errores, "Error dni incorrecto");
                    }
                } else {
                    array_push($errores, "Error dni incorrecto");
                }

                if (isset($datos["email"])) {
                    $email = filtrado($datos["email"]);
                    if (!validar_email($email)) {
                        array_push($errores, "Error email mal formado");
                    }
                } else {
                    array_push($errores, "Error email");
                }

                if (isset($datos["dir"])) {
                    $dir = filtrado($datos["dir"]);
                    if (strlen($dir)<5) {
                        array_push($errores, "Error direccion long menor de 5");
                    } 
                } else {
                    array_push($errores, "Error en direccion");
                }

                if (isset($datos["tel"])) {
                    $tel = filtrado($datos["tel"]);
                    if (!validarTelefono($tel)) {
                        array_push($errores, "Error numero de telefono invalido");
                    } 
                } else {
                    array_push($errores, "Error en numero de telefono");
                }

                if (isset($datos["fNaz"])) {
                    $fNaz = filtrado($datos["fNaz"]);
                    if (!validarFecha($fNaz)) {
                        array_push($errores, "Error fecha de alta mal formada");
                    } 
                } else {
                    array_push($errores, "Error en la fecha de alta");
                }

                if (isset($datos["fAlta"])) {
                    $fAlta = filtrado($datos["fAlta"]);
                    if (!validarFecha($fAlta)) {
                        array_push($errores, "Error fecha de alta mal formada");
                    } 
                } else {
                    array_push($errores, "Error en la fecha de alta");
                }

                if (isset($datos["saldo"])) {
                    $saldo = filtrado($datos["saldo"]);
                    if (empty($saldo)) {
                        array_push($errores, "Error el saldo no puede estar vacio");
                    } else if (is_numeric($saldo)) {
                        $saldo = intval($saldo);
                        if ($saldo < 1) {
                            array_push($errores, "Error el saldo no puede ser menor de 1");
                        }
                    }
                } else {
                    array_push($errores, "Error importe no recibido");
                }
                if (isset($datos["numCuen"])) {
                    $numCuen = filtrado($datos["numCuen"]);
                    if (empty($numCuen)) {
                        array_push($errores, "Error el numero de cuentas no puede estar vacio");
                    } else if (is_numeric($numCuen)) {
                        $numCuen = intval($numCuen);
                        if ($numCuen < 1) {
                            array_push($errores, "Error el numero de cuentas no puede ser menor de 1");
                        }
                    }
                } else {
                    array_push($errores, "Error numero de cuentas");
                }

                if (count($errores) > 0) {
                    echo json_encode(array('cliente'=>$errores));
                } else {
                    if ($clientes->grabarCliente($dni,$nombre,$dir,$tel,$email,$fNaz,$fAlta,$numCuen,$saldo) === true) {
                        echo json_encode(array('cliente'=> true));
                    }else{
                        echo json_encode(array('cliente'=> 'hubo un error al intentar registrar el cliente'));
                    }
                }
                break;
                case 5://actualiar numero de cuentas y saldo
                    if (isset($datos["dni"])) {
                        $dni = filtrado($datos["dni"]);
                        if (!validar_dni($dni)) {
                            array_push($errores, "Error dni incorrecto");
                        }
                    } else {
                        array_push($errores, "Error dni incorrecto");
                    }
                    if (isset($datos["importe"])) {
                        $saldo = filtrado($datos["importe"]);
                        if (empty($saldo)) {
                            array_push($errores, "Error el saldo no puede estar vacio");
                        } else if (is_numeric($saldo)) {
                            $saldo = intval($saldo);
                            if ($saldo < 1) {
                                array_push($errores, "Error el saldo no puede ser menor de 1");
                            }
                        }
                    } else {
                        array_push($errores, "Error importe no recibido");
                    }
                    
    
                    if (count($errores) > 0) {
                        echo json_encode(array('cliente'=>$errores));
                    } else {
                        if ($clientes->modificarCliente($dni,$saldo) === true) {
                            echo json_encode(array('cliente'=> true));
                        }else{
                            echo json_encode(array('cliente'=> 'hubo un error al intentar actualizar'));
                        }
                    }
                break;
                case 5://modificar saldo
                    if (isset($datos["dni"])) {
                        $dni = filtrado($datos["dni"]);
                        if (!validar_dni($dni)) {
                            array_push($errores, "Error dni incorrecto");
                        }
                    } else {
                        array_push($errores, "Error dni incorrecto");
                    }
                    if (isset($datos["importe"])) {
                        $saldo = filtrado($datos["importe"]);
                        if (empty($saldo)) {
                            array_push($errores, "Error el saldo no puede estar vacio");
                        } else if (is_numeric($saldo)) {
                            $saldo = intval($saldo);
                            if ($saldo < 1) {
                                array_push($errores, "Error el saldo no puede ser menor de 1");
                            }
                        }
                    } else {
                        array_push($errores, "Error importe no recibido");
                    }
                    
                    if (isset($datos["operacion"])) {
                        $operacion = filtrado($datos["operacion"]);
                        if (empty($operacion)) {
                            array_push($errores, "Error operacion no puede estar vacio");
                        } else if (is_numeric($operacion)) {
                            $operacion = intval($operacion);
                            if ($operacion !== 1 && $operacion !==2) {
                                array_push($errores, "Error operacion desconocida");
                            }else{
                                if($operacion===1){
                                    $saldo=$saldo*(-1);
                                }
                            }
                        }else{
                            array_push($errores, "Error no es valida");
                        }
                    } else {
                        array_push($errores, "Error operacion no recibido");
                    }
    
                    if (count($errores) > 0) {
                        echo json_encode(array('cliente'=>$errores));
                    } else {
                        if ($clientes-> modificarSaldo($dni,$saldo) === true) {
                            echo json_encode(array('cliente'=> true));
                        }else{
                            echo json_encode(array('cliente'=> 'hubo un error al intentar actualizar'));
                        }
                    }
                break;
        }
    } else {
        include('views/cierreCuentas.php');
    }
} else {
    header("Location: index1.php?mensaje=hubo un error al recibir los datos");
}
