<?php

require_once('Conectar.php');

class Productos_modelo
{
    private $db;
    private $productos;

    public function __construct()
    {

        $this->db = Conectar::coneccion();
        $this->productos = array();
    }

    public function verificaProducto($idProducto)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM productos WHERE codigo=:cod");
            $stmt->execute(array(':cod' => $idProducto));
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
    public function crearProducto($codigo, $nombre, $precio, $stock)
    {
        try {
            if (!$this->verificaProducto($codigo)) {

                if ($codigo != "") {
                    $stmt = $this->db->prepare("INSERT INTO productos(codigo,nombre,precio,stock) VALUES(:cod,:nom,:prec,:stock)");
                    $stmt->execute(array(':cod' => $codigo, ':nom' => $nombre, ':prec' => $precio, ':stock' => $stock));
                } else {
                    $stmt = $this->db->prepare("INSERT INTO productos(nombre,precio,stock) VALUES(:cod,:nom,:prec,:stock)");
                    $stmt->execute(array(':nom' => $nombre, ':prec' => $precio, ':stock' => $stock));
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
