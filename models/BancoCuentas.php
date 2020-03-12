<?php

require_once('models/auxi/Conectar.php');

class BancoCuentas
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

    public function getClientes($nCuenta)
    {
        try {
            if (!$this->verificaCuenta($nCuenta)) {
                $stmt = $this->db->prepare("SELECT cu_dn1,cu_dn2 FROM cuentas WHERE cu_ncu=:ncu");
                $stmt->execute(array('ncu' => $nCuenta));

                $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($datos) == 0) {
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
    public function grabarRegistro($nCuenta, $dni1, $dni2, $saldo)
    {
        try {
            if (!$this->verificaCuenta($nCuenta)) {

                if ($dni2 != "") {
                    $stmt = $this->db->prepare("INSERT INTO cuentas(cu_ncu,cu_dn1,cu_sal) VALUES(:nc,:dni1,:saldo)");
                    $stmt->execute(array(':nc' => $nCuenta, ':dni1' => $dni1, ':saldo' => $saldo));
                } else {
                    $stmt = $this->db->prepare("INSERT INTO productos(cu_ncu,cu_dn1,cu_dn2,cu_sal) VALUES(:nc,:dni1,:dni2,:saldo)");
                    $stmt->execute(array(':nc' => $nCuenta, ':dni1' => $dni1, ':dni2' => $dni2, ':saldo' => $saldo));
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

    public function getSaldo($nCuenta)
    {
        try {
            if ($this->verificaCuenta($nCuenta)) {
                $stmt = $this->db->prepare("SELECT cu_sal FROM cuentas WHERE cu_ncu=:ncu");
                $stmt->execute(array('ncu' => $nCuenta));

                $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($datos) == 0) {
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
    public function cierreCuenta($nCuenta)
    {
        try {
            if ($this->verificaCuenta($nCuenta)) {

                $stmt = $this->db->prepare("DELETE FROM cuentas WHERE cu_ncu=:ncu");
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


    public function modificarSaldo($nCuenta, $saldo)
    {
        try {
            if ($this->verificaCuenta($nCuenta)) {

                $stmt = $this->db->prepare("UPDATE cuentas SET cu_sal=cu_sal+ (:sal) where cu_ncu=:ncu");
                $stmt->execute(array(':sal' => $saldo, ':ncu' => $nCuenta));
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

    public function getDb()
    {
        return $this->db;
    }
}
