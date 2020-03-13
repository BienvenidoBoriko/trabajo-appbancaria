<?php

require_once('models/auxi/Conectar.php');

class BancoMovimientos
{
    private $db;
    public function __construct()
    {
        $this->db = Conectar::coneccion();
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

    public function getMovimientos($nCuenta, $desde, $hasta)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM movimientos WHERE mo_fec BETWEEN :desde AND :hasta");
            $stmt->execute(array('desde' => $desde, 'hasta' => $hasta));

            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($datos) == 0) {
                $datos = false;
            }
            $stmt = null;
        } catch (PDOException $e) {
            die("¡Error!: " . $e->getMessage() . "<br/>");
            $datos = -2;
        }
        return $datos;
    }
    public function grabarRegistro($nCuenta, $fecha, $hora, $desc, $importe)
    {
        try {

            $stmt = $this->db->prepare("INSERT INTO movimientos(mo_ncu,mo_fec,mo_hor,mo_des,mo_imp) VALUES(:nc,:fecha,:hora,:descr,:impor)");
            $stmt->execute(array(':nc' => $nCuenta, ':fecha' => $fecha, ':hora' => $hora, ':descr' => $desc, ':impor' => $importe));

            if ($stmt->rowCount() > 0) {
                $autenticado = true;
            } else {
                $autenticado = false;
            }
            $stmt = null;
            return $autenticado;
        } catch (PDOException $e) {
            die("¡Error!: " . $e->getMessage() . "<br/>");
        }
    }

    public function eliminarMovimientos($nCuenta)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM movimientos WHERE mo_ncu=:ncu");
            $stmt->execute(array(':ncu' => $nCuenta));
            if ($stmt->rowCount() > 0) {
                $eliminado = true;
            } else {
                $eliminado = false;
            }
            $stmt = null;
            return $eliminado;
        } catch (PDOException $e) {
            die("¡Error!: " . $e->getMessage() . "<br/>");
        }
    }

    public function getDb()
    {
        return $this->db;
    }
}
