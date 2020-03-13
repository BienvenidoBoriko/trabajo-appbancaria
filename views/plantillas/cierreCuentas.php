<h1>Lista de Movimientos</h1>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" name="formu">
    <div class="form-group col-md-6">
        <label for="id_nCuenta">Numero de Cuenta
            <input type="number" name="nCuenta" required='required' class="form-control" id="id_nCuenta" required="required" maxlength="10" <?php if (isset($_POST["nCuenta"])) echo "value=\"" . $_POST["nCuenta"] . "\""; ?> />
            <span id="enc"></span>
        </label>
    </div>
    <input type="button" value="Cerrar" name="cerrar">
</form>
<div id="datos">
    <table id="cuenta">
        <tbody id="c_tbody">

        </tbody>
    </table>

    <table id="titularesC">
        <tbody id="tc_tbody">

        </tbody>
    </table>
</div>