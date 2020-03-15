<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $titulo; ?></title>
    <!--<link rel="stylesheet" href="views/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="<?php echo $script ?>"></script>
    <link rel="stylesheet" href="views/css/style.css">
    <script type="text/javascript">
        $(document).ready(function() {
            $("body").fadeIn(2000);
            $("img").fadeIn(2000);
        });
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="index.php">Banco</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index1.php">Home<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index1.php?vista=aper_cuentas">Apertura de Cuentas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index1.php?vista=l_movimientos">Listar Movimientos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index1.php?vista=cierreCuentas">Cierre de Cuentas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index1.php?vista=ingresos_reintegros">Ingresos Y Reintegros</a>
                </li>
            </ul>
        </div>
    </nav>
    <?php include($plantilla) ?>
</body>

</html>