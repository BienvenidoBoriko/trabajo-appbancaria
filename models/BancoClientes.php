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



    public function modificarCliente($dni,$saldo)
    {
        try {
            if ($this->verificaProducto($codigoA)) {

                $stmt = $this->db->prepare("UPDATE clientes SET cl_ncu=cl_ncu+1,cl_sal=cl_sal+ :sal where cl_dni=:dni");
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
}
