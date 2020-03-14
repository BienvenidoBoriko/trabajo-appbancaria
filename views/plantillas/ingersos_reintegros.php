<div class="container">

    <h3>Ingresos y Reintegros</h3>
    <form action="" method="post">
        <div class="form-group col-md-6">
            <input type="radio" id="reintegro" name="operacion" value="1">
            <label for="male">Reintegro</label><br>
            <input type="radio" id="ingreso" name="operacion" value="2">
            <label for="female">Ingreso</label>
        </div>
        <div class="form-group col-md-6">
            <label for="id_nCuenta">Numero de Cuenta
                <input type="number" name="nCuenta" required='required' class="form-control" id="id_nCuenta" required="required" maxlength="10" <?php if (isset($_POST["nCuenta"])) echo "value=\"" . $_POST["nCuenta"] . "\""; ?> />
                <span id="enc"></span>
            </label>
        </div>
        <div class="form-group col-md-6">
            <label for="id_desc">Descripcion
                <input type="text" class="form-control" name="desc" id="id_dni2" disabled="disabled" placeholder="opcional"><span>*</span>
                <span id="edni2"></span>
            </label>
        </div>
        <div class="form-group col-md-6">
            <label for="id_importe">Importe
                <input type="number" name="importe" class="form-control" id="id_importe" disabled="disabled" required="required">
                <span id="eimp"></span>
            </label>
        </div>
        <div class="form-group col-md-6">
            <input type="button" class="btn btn-primary" value="Registrar" name="registrarCliente" id="registrarCliente">
        </div>
        <div id="errForm2"></div>
    </form>
</div>