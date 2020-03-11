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

    public function get_productos($desde, $hasta)
    {
        try {
            $desde = intval($desde);
            $hasta = intval($hasta);
            if ($desde === 0 && $hasta === 0) {
                $stmt = $this->db->query("SELECT * FROM productos");
            } else if ($hasta !== 0) {
                $stmt = $this->db->query("SELECT * FROM productos limit $desde,$hasta");
            }

            if (!count($datos = $stmt->fetchAll(PDO::FETCH_ASSOC)) > 0) {
                $datos = false;
            }
            $res = null;
        } catch (PDOException $e) {
            die("¡Error!: " . $e->getMessage() . "<br/>");
        }
        return $datos;
    }
    public function grabarRegistro($nCuenta, $fecha,$hora,$desc,$importe)
    {
        try {
            if (!$this->verificaCuenta($nCuenta)) {

                if ($dni2 != "") {
                    $stmt = $this->db->prepare("INSERT INTO movimientos(cu_ncu,cu_dn1,cu_sal) VALUES(:nc,:dni1,:saldo)");
                    $stmt->execute(array(':nc' => $nCuenta, ':dni1' => $dni1,':saldo' => $saldo));
                } else {
                    $stmt = $this->db->prepare("INSERT INTO productos(cu_ncu,cu_dn1,cu_dn2,cu_sal) VALUES(:nc,:dni1,:dni2,:saldo)");
                    $stmt->execute(array(':nc' => $nCuenta, ':dni1' => $dni1,':dni2' => $dni2,':saldo' => $saldo));
                }

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

    public function borrarProducto($idProducto)
    {
        try {
            if ($this->verificaProducto($idProducto)) {

                $stmt = $this->db->prepare("DELETE FROM productos WHERE codigo=:cod");
                $stmt->execute(array(':cod' => $idProducto));
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
