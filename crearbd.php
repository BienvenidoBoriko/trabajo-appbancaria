<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <?php
    if (isset($_POST['crear']) || isset($_POST['borar'])) {
        $db = new mysqli("127.0.0.1", "root", "");
        if ($db->connect_errno) {
            echo "Falló conexión a MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        }
        if (isset($_POST['crear'])) {
            $sentencia = <<<EOT
            SET PASSWORD = PASSWORD("");
                    DROP database IF EXISTS banco;
                    CREATE  DATABASE   IF NOT EXISTS banco;
                    use banco;
                    CREATE TABLE clientes (cl_dni VARCHAR(9)  NOT NULL, 
                        cl_nom VARCHAR(50) NOT NULL, 
                        cl_dir VARCHAR(60) NOT NULL, 
                        cl_tel VARCHAR(9)  NOT NULL, 
                        cl_ema VARCHAR(65) NOT NULL, 
                        cl_fna DATE,
                        cl_fcl DATE        NOT NULL, 
                        cl_ncu TINYINT(2)  NOT NULL, 
                        cl_sal INT(8)      NOT NULL, 
                        PRIMARY KEY (cl_dni)) ENGINE = MYISAM;

                    CREATE TABLE cuentas (cu_ncu VARCHAR(10)  NOT NULL,
                        cu_dn1 VARCHAR(9)   NOT NULL, 
                        cu_dn2 VARCHAR(9), 
                        cu_sal INT(8)      NOT NULL, 
                        FOREIGN KEY (cu_dn1, cu_dn2) REFERENCES clientes(cl_dni, cl_dni)
                        ) ENGINE = MYISAM;

                    CREATE TABLE movimientos (mo_ncu VARCHAR(10)  NOT NULL,
                        mo_fec DATE         NOT NULL, 
                        mo_hor VARCHAR(6)   NOT NULL,
                        mo_des VARCHAR(80)  NOT NULL,
                        mo_imp INT(8)       NOT NULL,
                        PRIMARY KEY (mo_ncu, mo_fec, mo_hor)) ENGINE = MYISAM;
EOT;

            if (!$db->multi_query($sentencia)) { // Ejecuta el conjunto de ordenes de $orden
                echo "Falló la multiconsulta: (" . $db->errno . ") " . $db->error;
            } else {
                echo "<p>base de datos creada con exito</p>";
            }
        } else if (isset($_POST['borar'])) {

            if (!$db->query('DROP DATABASE Banco')) {
                echo '<p>Error al borrar la tabla</p>';
            } else {
                echo "<p>base de datos eliminada con exito</p>";
            }
        }
    } else {
    ?>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
            <input class="btn btn-primary mt-5 mb-5" type="submit" name="crear" value="Crear Bd">
            <input class="btn btn-primary mt-5 mb-5" type="submit" name="borar" value="Borrar Bd">
        </form>
    <?php
    }
    ?>
</body>

</html>