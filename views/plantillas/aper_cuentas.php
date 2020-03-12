<h2 class="title">Apertura de cuentas </h2>
    <form action="" method="post">
        <div class="form-group col-md-6">
            <label for="id_nCuenta">Numero de Cuenta
            <input type="number" name="nCuenta" class="form-control" id="id_nCuenta" required="required" maxlength="10">
            <span id="enc"></span>
        </label>
        </div>
        <div class="form-group col-md-6">
            <label for="id_dni1">Dni1
            <input type="text" class="form-control" name="dni1" id="id_dni1" disabled="disabled" required="required">
            <span id="edni1"></span>
        </label>
        </div>
        <div class="form-group col-md-6">
            <label for="id_dni2">Dni2
            <input type="text" class="form-control" name="dni2" id="id_dni2" disabled="disabled" placeholder="opcional"><span>*</span>
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
            <input type="button" class="btn btn-primary" value="Registrar" name="registrar" id="id_registrar">
        </div>
    </form>