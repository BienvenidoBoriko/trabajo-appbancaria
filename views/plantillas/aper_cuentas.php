<div class="container aper_cuentas">
<div>
    <h2 class="title">Apertura de cuentas </h2>
    <form action="" method="post">
        <div class="form-group col-md-6">
            <label for="id_nCuenta">Numero de Cuenta
                <input type="number" name="nCuenta" class="form-control" id="id_nCuenta" required="required" maxlength="10">
                <span id="enc"></span>
            </label>
        </div>
        <div class="form-group col-md-6">
            <label for="id_dni1">Dni del primer titular
                <input type="text" class="form-control" name="dni1" id="id_dni1" disabled="disabled" required="required">
                <span id="edni1"></span>
            </label>
        </div>
        <div class="form-group col-md-6">
            <label for="id_dni2">Dni del segundo titular
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
        <div id="errForm1"></div>
    </form>
    </div>


    <div id="registrarClientes">
        <h3>Registrar Usuario</h3>
        <form action="" method="post">
            <div class="form-group col-md-6">
                <label for="id_dni">Dni
                    <input type="text" class="form-control" name="dni" id="id_dni" required="required">
                    <span id="edni"></span>
                </label>
            </div>
            <div class="form-group col-md-6">
                <label for="id_nom">Nombre
                    <input type="text" name="nom" class="form-control" id="id_nom" required="required">
                    <span id="enom"></span>
                </label>
            </div>

            <div class="form-group col-md-6">
                <label for="id_dir">Direccion
                    <input type="text" class="form-control" name="dir" id="id_dir"  required="required">
                    <span id="edir"></span>
                </label>
            </div>
            <div class="form-group col-md-6">
                <label for="id_tel">Telefono
                    <input type="tel" name="tel" class="form-control" id="id_tel"  required="required">
                    <span id="etel"></span>
                </label>
            </div>
            <div class="form-group col-md-6">
                <label for="id_email">Email
                    <input type="email" name="email" class="form-control" id="id_email" required="required">
                    <span id="eemail"></span>
                </label>
            </div>
            <div class="form-group col-md-6">
                <label for="id_fNaz">Fecha de Nacimiento
                    <input type="date" name="fNaz" class="form-control" id="id_fNaz"  required="required">
                    <span id="efNaz"></span>
                </label>
            </div>
            <div class="form-group col-md-6">
                <input type="button" class="btn btn-primary" value="Registrar" name="registrarCliente" id="registrarCliente">
            </div>
            <div id="errForm2"></div>
        </form>
    </div>
</div>
