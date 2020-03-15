<h1>Cierre de Cuentas</h1>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" name="formu">
    <div class="form-group col-md-6">
        <label for="id_nCuenta">Numero de Cuenta
            <input type="number" name="nCuenta" required='required' class="form-control" id="id_nCuenta" required="required" maxlength="10" <?php if (isset($_POST["nCuenta"])) echo "value=\"" . $_POST["nCuenta"] . "\""; ?> />
            <span id="enc"></span>
        </label>
    </div>
    <div class="form-group col-md-6">
        <input type="button" class="btn btn-primary" value="Cerrar" name="cerrar" id="realizar">
    </div>
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