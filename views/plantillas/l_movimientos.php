<div class="container movi">
    <h1>Lista de Movimientos</h1>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" name="formu">
        <div class="form-group col-md-6">
            <label for="id_nCuenta">Numero de Cuenta
                <input type="number" name="nCuenta" required='required' class="form-control" id="id_nCuenta" required="required" maxlength="10" <?php if (isset($_POST["nCuenta"])) echo "value=\"" . $_POST["nCuenta"] . "\""; ?> />
                <span id="enc"></span>
            </label>
        </div>
        <div class="form-group col-md-6">
            <label for="id_fechaP">Fecha primer movimiento
                <input type="date" required='required' name="fechaP" id="id_fechaP" <?php if (isset($_POST["fechaP"])) echo "value=\"" . $_POST["fechaP"] . "\""; ?> />
                <span id="efp"></span>
            </label>
        </div>
        <div class="form-group col-md-6">
            <label for="id_fechaU">Fecha ultimo movimiento
                <input type="date" required='required' name="fechaU" id="id_fechaU" <?php if (isset($_POST["fechaU"])) echo "value=\"" . $_POST["fechaU"] . "\""; ?> />
                <span id="efu"></span>
            </label>
        </div>
        <div class="form-group col-md-6">
            <input type="button" class="btn btn-primary" value="Ver" name="ver" id="realizar">
        </div>

    </form>
    <div id="moverror"></div>
    <h2>Movimientos</h2>
    <table id="l_movimientos" class="table table-striped ">
        <thead class="thead-dark">
            <tr>
                <th>Num-cuenta</th>
                <th>Fecha-mov</th>
                <th>Hora-mov</th>
                <th>Descripcion-mov</th>
                <th>Importe-mov</th>
            </tr>
        </thead>
        <tbody id="lm_tbody">

        </tbody>
    </table>
</div>