let cValidos = Array();
document.addEventListener("readystatechange", () => {
    if (document.readyState == "interactive") {
        document.forms[0].nCuenta.addEventListener("focusout", validarCuenta, false);
        document.forms[0].realizar.addEventListener("click", realizar, false);
        document.getElementById('l_movimientos').style.display = 'none';
    }
}, false);


function validarCuenta(e) {
    let nCuentaOk = true;
    let nCuenta = (e.target.value + "").split("").map((x) => Number(x));
    if (nCuenta.length === 10) {
        let cpNcuenta = [...nCuenta];
        cpNcuenta.pop();
        if (cpNcuenta.reduce((acu, valor) => acu + valor) % 9 === nCuenta[nCuenta.length - 1]) {
            nCuentaOk = true;
        } else {
            nCuentaOk = false
        }

    } else {
        nCuentaOk = false;
    }

    if (nCuentaOk) {
        document.getElementById('enc').innerText = ' ';
        if (cValidos.find((v) => v == 'nCuenta') === undefined) {
            cValidos.push('nCuenta');
        }

    } else {
        cValidos = cValidos.filter((v) => v != 'nCuenta');
        console.log(cValidos);
        document.getElementById('enc').innerText = 'numero de cuenta incorrecto'
    }
}

function validar() {
    let ok = true;
    let importe = document.forms[0].importe.value;
    if (document.forms[0].operacion.value == '') {
        ok = false;
        document.getElementById('eopr').innerText = 'Error tienes que seleccionar una operacion';
    } else {
        document.getElementById('eopr').innerText = '';
    }

    if (document.forms[0].desc.value !== '') {
        if (document.forms[0].desc.value.length < 5) {
            document.getElementById('edesc').innerText = 'Error longitud mayor de 5';
        } else {
            document.getElementById('edesc').innerText = '';
        }
    } else {
        document.getElementById('edesc').innerText = 'Error desc no puede esta vacio';
        ok = false;
    }

    if (importe !== '') {
        importe = parseInt(importe);
        if (importe < 1) {
            document.getElementById('eimp').innerText = 'Error importe tiene que ser mayor de 1';
        } else {
            document.getElementById('eimp').innerText = '';
        }
    } else {
        document.getElementById('eimp').innerText = 'Error importe no puede esta vacio';
        ok = false;
    }

    if (cValidos.length !== 1) {
        ok = false;
    }
    return ok;
}

function realizar(e) {
    if (validar()) {
        let nCuenta = document.forms[0].nCuenta.value;
        let importe = document.forms[0].importe.value;
        let desc = document.forms[0].desc.value;
        let operacion = document.forms[0].operacion.value;
        let mensaje = ` Se va ha realizar la operacion ${ nCuenta==1 ?'reintegro': 'ingreso' } \n con los siguientes datos 
        nº de cuenta = ${nCuenta}, \n operacion = ${ nCuenta==1 ?'reintegro': 'ingreso' }, importe = ${importe}, descripcion ${desc}\n \n Estas conforme ?`
        let ok = confirm(mensaje);
        if (ok) {
            let resSal = modSaldoCuenta(nCuenta, importe, operacion);
            if (resSal['cuenta']) {
                let clientes = pedirClientes(nCuenta);
                if (clientes['titulares'] !== undefined) {
                    modSaldoCliente(clientes['titulares']['cu_dn1'], importe, operacion);
                    if (clientes['titulares']['cu_dn1'] !== undefined) {
                        modSaldoCliente(clientes['titulares']['cu_dn2'], importe, operacion);
                    }
                    registrarMovi(nCuenta, desc, importe);
                } else {
                    alert('hubo un error al obtener los titulares');
                }

            } else {
                alert('hubo un error al modificar el saldo en la cuenta')
            }


        }
    }

}

function pedirClientes(nCuenta) {
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 5 });
    let peticion = new XMLHttpRequest();
    peticion.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            let dat = JSON.parse(this.responseText, true);
            return dat;
        }
    }
    peticion.open('POST', "index1.php", false);
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send(`dato=${dato}&cont=cuentas`);
}

function modSaldoCuenta(nCuenta, importe, operacion) {
    let dato = JSON.stringify({ nCuenta: nCuenta, importe: importe, operacion: operacion, opr: 6 });
    let peticion = new XMLHttpRequest();
    peticion.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            let dat = JSON.parse(this.responseText, true);
            return dat;
        }
    }
    peticion.open('POST', "index1.php", false);
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send(`dato=${dato}&cont=cuentas`);
}

function modSaldoCliente(dni, importe, operacion) {
    let dato = JSON.stringify({ dni: dni, importe: importe, opr: 5 });
    let peticion = new XMLHttpRequest();
    peticion.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            let dat = JSON.parse(this.responseText, true);
            return dat;
        }
    }
    peticion.open('POST', "index1.php", false);
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send(`dato=${dato}&cont=cliente`);
}

function registrarMovi(nCuenta, desc, importe) {
    let dato = JSON.stringify({ nCuenta: nCuenta, desc: desc, importe: importe, opr: 3 });
    let peticion = new XMLHttpRequest();
    peticion.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            let dat = JSON.parse(this.responseText, true);
            if (dat['move'] === true) {
                alert(`movimiento ${desc} registrado`);
            } else {
                document.getElementById('errForm1').innerText = dat['move'];
            }
        }
    }
    peticion.open('POST', "index1.php", false);
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send(`dato=${dato}&cont=movimientos`);
}

/* function pintarR(estado, dato) {
    console.log(dato);
} */