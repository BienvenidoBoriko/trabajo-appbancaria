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
    <h3>Datos de la cuenta</h3>
    <table id="cuenta" class="table">
        <thead class="thead-dark">
            <tr>
                <td>Nº Cuenta</td>
                <td>Titular 1</td>
                <td>Titular 2</td>
                <td>Saldo</td>
            </tr>
        </thead>
        <tbody id="c_tbody">

        </tbody>
    </table>
    <h3>Datos de los Titulares</h3>
    <table id="titularesC" class="table">
        <thead class="thead-dark">
            <tr>
                <td>Dni</td>
                <td>Direccion</td>
                <td>Nombre</td>
                <td>Telefono</td>
                <td>Email</td>
                <td>F_Nacimiento</td>
                <td>F_Alta</td>
                <td>Saldo</td>
            </tr>
        </thead>
        <tbody id="tc_tbody">

        </tbody>
    </table>
</div>