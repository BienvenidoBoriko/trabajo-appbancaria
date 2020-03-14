<?php

require_once('models/auxi/Conectar.php');

class BancoClientes
{
    private $db;

    public function __construct()
    {
        $this->db = Conectar::coneccion();
    }

    public function verificaCliente($dni)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM clientes WHERE cl_dni=:dni");
            $stmt->execute(array(':dni' => $dni));
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

    public function grabarCliente($dni,$nombre,$dir,$tel,$ema,$fna,$fcl,$nCueAbiertas,$saldo)
    {
        try {
            if (!$this->verificaCliente($dni)) {
                
                $stmt = $this->db->prepare("INSERT INTO clientes(cl_dni,cl_nom,cl_dir,cl_tel,cl_ema,cl_fna,cl_fcl,cl_ncu,cl_sal) VALUES(:dni,:nom,:dir,:tel,:ema,:fna,:fcl,:ncua,:sal)");
                $stmt->execute(array(':dni'=> $dni,':nom'=>$nombre,':dir'=>$dir,':tel'=>$tel,':ema'=>$ema,':fna'=>$fna,':fcl'=>$fcl,':ncua'=>$nCueAbiertas,':sal'=>$saldo));

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

    public function eliminarClientes($dni)
    {
        try {
            if ($this->verificaCliente($dni)) {

                $stmt = $this->db->prepare("DELETE FROM clientes WHERE cl_dni=:dni");
                $stmt->execute(array(':dni' => $dni));
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

    public function getNumCuenta($dni)
    {
        try {
            if ($this->verificaCliente($dni)) {

                $stmt = $this->db->prepare("SELECT cl_ncu FROM clientes WHERE cl_dni=:dni");
                $stmt->execute(array(':dni' => $dni));
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
        }
        return $datos;
    }

    public function getDatosCliente($dni)
    {
        try {
            if ($this->verificaCliente($dni)) {

                $stmt = $this->db->prepare("SELECT * FROM clientes WHERE cl_dni=:dni");
                $stmt->execute(array(':dni' => $dni));
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
        }
        return $datos;
    }

    public function modificarNumCuentas($dni)
    {
        try {
            if ($this->verificaCliente($dni)) {

                $stmt = $this->db->prepare("UPDATE clientes SET cl_ncu=cl_ncu-1 where cl_dni=:dni");
                $stmt->execute(array(':dni'=>$dni));
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

    public function modificarCliente($dni,$saldo)
    {
        try {
            if ($this->verificaCliente($dni)) {

                $stmt = $this->db->prepare("UPDATE clientes SET cl_ncu=cl_ncu+1,cl_sal=cl_sal+ (:sal) where cl_dni=:dni");
                $stmt->execute(array(':sal'=>$saldo,':dni'=>$dni));
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

    public function modificarSaldo($dni,$saldo)
    {
        try {
            if ($this->verificaCliente($dni)) {

                $stmt = $this->db->prepare("UPDATE clientes SET cl_sal=cl_sal+ (:sal) where cl_dni=:dni");
                $stmt->execute(array(':sal'=>$saldo,':dni'=>$dni));
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

    public function getDb() {
        return $this->db;
    }
}
