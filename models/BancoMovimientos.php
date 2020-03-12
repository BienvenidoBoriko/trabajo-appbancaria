<?php

require_once('Conectar.php');

class BancoCuentas
{
    private $db;
    private $productos;

    public function __construct()
    {

        $this->db = Conectar::coneccion();
        $this->productos = array();
    }

    public function verificaCuenta($nCuenta)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM cuentas WHERE cu_ncu=:nc");
            $stmt->execute(array(':nc' => $nCuenta));
            if (count($stmt->fetchAll()) > 0) {
                $autenticado = true;
            } else {
                $autenticado = false;
            }
            $res = null;
        } catch (PDOException $e) {
            die("¡Error!: " . $e->getMessage() . "<br/>");
        }
        return $autenticado;
    }

    public function getMovimientos($nCuenta,$desde, $hasta)
    {
        try {
            if (!$this->verificaCuenta($nCuenta)) {
                $stmt = $this->db->prepare("SELECT * FROM movimientos WHERE mo_fec BETWEEN :desde AND :hasta");
                $stmt->execute(array('desde'=>$desde,'hasta'=>$hasta));

                $datos=$stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if(count($datos)==0){
                    $datos = false;
                } 
                $stmt = null;
            } else {
                $datos = -1;
            }
        } catch (PDOException $e) {
            die("¡Error!: " . $e->getMessage() . "<br/>");
            $datos = -2;
        }
        return $datos;
    }
    public function grabarRegistro($nCuenta, $fecha,$hora,$desc,$importe)
    {
        try {
            if (!$this->verificaCuenta($nCuenta)) {

                $stmt = $this->db->prepare("INSERT INTO movimientos(mo_ncu,mo_fec,mo_hor,mo_des,mo_imp) VALUES(:nc,:fecha,:hora,:descr,:impor)");
                $stmt->execute(array(':nc'=>$nCuenta,':fecha'=>$fecha,':hora'=>$hora,':descr'=>$desc,':impor'=>$importe));
                
                if ($stmt->rowCount() > 0) {
                    $autenticado = true;
                } else {
                    $autenticado = false;
                }
                $stmt = null;
                return $autenticado;
            } else {
                return -1;
            }
        } catch (PDOException $e) {
            die("¡Error!: " . $e->getMessage() . "<br/>");
        }
        return $autenticado;
    }

    public function eliminarMovimientos($nCuenta)
    {
        try {
            if ($this->verificaCuenta($nCuenta)) {

                $stmt = $this->db->prepare("DELETE FROM movimientos WHERE mo_ncu=:ncu");
                $stmt->execute(array(':ncu' => $nCuenta));
                if ($stmt->rowCount() > 0) {
                    $eliminado = true;
                } else {
                    $eliminado = false;
                }
                $stmt = null;
                return $eliminado;
            } else {
                return -1;
            }
        } catch (PDOException $e) {
            die("¡Error!: " . $e->getMessage() . "<br/>");
        }
    }



    public function modificarProducto($codigoA, $codigoN, $nombre, $precio, $stock)
    {
        try {
            if ($this->verificaProducto($codigoA)) {

                $stmt = $this->db->prepare("UPDATE productos SET codigo=:cod,nombre=:nom,precio=:pre,stock=:st where codigo=:codA");
                $stmt->execute(array(':cod' => $codigoN, ':nom' => $nombre, ':pre' => $precio, ':st' => $stock, ':codA' => $codigoA));
                if ($stmt->rowCount() > 0) {
                    $modificado = true;
                } else {
                    $modificado = false;
                }
                $stmt = null;
                return $modificado;
            } else {
                return -1;
            }
        } catch (PDOException $e) {
            die("¡Error!: " . $e->getMessage() . "<br/>");
        }
    }
}
