let cValidos = Array();
document.addEventListener("readystatechange", () => {
    if (document.readyState == "interactive") {
        document.forms[0].nCuenta.addEventListener("focusout", validarCuenta, false);
        document.forms[0].ver.addEventListener("click", realizar, false);
    }
}, false);

function validarFechas(fecha1, fecha2) {
    let fechasOk = '';
    fecha1 = new Date(fecha1);
    fecha2 = new Date(fecha2);
    console.log(fecha2.getTime() + "de" + fecha1.getTime())
    if (fecha2.getTime() > fecha1.getTime()) {
        fechasOk = true;
    }

    return fechasOk;
}

function validarNumCuenta(nCuenta) {
    let nCuentaOk = true;
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 3 });
    let peticion = new XMLHttpRequest();
    peticion.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            let dat = JSON.parse(this.responseText);
            if (dat['cuenta'] === true) {
                nCuentaOk = true;
            } else if (dat['cuenta'] === false) {
                nCuentaOk = -1;
            } else if (dat['cuenta'] === -1) {
                nCuentaOk = -2;
            }
            return nCuenta;
        }
    }
    peticion.open('POST', "index1.php", true);
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send(`dato=${dato}&cont=cuentas`);
}

function verSaldoCuenta(nCuenta) {
    let nCuentaOk = true;
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 4 });
    let peticion = new XMLHttpRequest();
    peticion.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            let dat = JSON.parse(this.responseText);
            if (dat['saldo'] == 0) {
                nCuentaOk = -3;
            } else if (dat['saldo'] === false) {
                nCuentaOk = false;
            } else {
                nCuentaOk = true;
            }
            return nCuenta;
        }
    }
    peticion.open('POST', "index1.php", true);
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send(`dato=${dato}&cont=cuentas`);
}

function validarCuenta(e) {
    let nCuentaOk = true;
    let nCuenta = (e.target.value + "").split("").map((x) => Number(x));
    if (nCuenta.length === 10) {
        let cpNcuenta = [...nCuenta];
        cpNcuenta.pop();
        if (cpNcuenta.reduce((acu, valor) => acu + valor) % 9 === nCuenta[nCuenta.length - 1]) {
            nCuentaOk = validarNumCuenta(e.target.value);
            if (nCuentaOk) {
                nCuentaOk = verSaldoCuenta(e.target.value);
            }
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
        pintarDatos(e.target.value);
    } else {
        cValidos = cValidos.filter((v) => v != 'nCuenta');
        if (nCuentaOk === false || nCuentaOk === -2) {
            document.getElementById('enc').innerText = 'numero de cuenta incorrecto';
        } else if (nCuentaOk === -1) {
            document.getElementById('enc').innerText = 'La cuenta no existe';
        } else if (nCuentaOk === -3) {
            document.getElementById('enc').innerText = 'La cuenta no esta vacia';
        }
    }
}

function validar() {
    let ok = true;
    if (cValidos.length !== 1) {
        ok = false;
    }
    return ok;
}

function realizar(e) {
    console.log('click');
    if (validar()) {
        let nCuenta = document.forms[0].nCuenta.value;

        let mensaje = ` Se van a pedir los registros que correspondan con los siguientes datos \n nº de cuenta = ${nCuenta}, \n fechaP = ${fechaP}, \n fechaU = ${fechaU}, \n Estas conforme ?`
        let ok = confirm(mensaje);
        if (ok) {
            if (eliminarCuenta(nCuenta)['cierre'] === true) {
                if (eliminarClientes(nCuenta)['el_client'] === true) {
                    if (eliminarMov(nCuenta)['el_mov'] === true) {
                        alert('datos eliminados con exito');
                    } else {
                        alert('Hubo un error al eliminar los movimientos');
                    }
                } else {
                    alert('Hubo un error al eliminar los clientes');
                }
            } else {
                alert('Hubo un error al eliminar la cuenta');
            }
        }
    }

}

function eliminarCuenta(nCuenta) {
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 2 });
    let peticion = new XMLHttpRequest();
    peticion.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            let dat = JSON.parse(this.responseText, true);
            pintarRespuesta(dat);
        }
    }
    peticion.open('POST', "index1.php", true);
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send(`dato=${dato}&cont=cuentas`);
}

function eliminarMov(nCuenta) {
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 2 });
    let peticion = new XMLHttpRequest();
    peticion.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            let dat = JSON.parse(this.responseText, true);
            pintarRespuesta(dat);
        }
    }
    peticion.open('POST', "index1.php", true);
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send(`dato=${dato}&cont=movimientos`);
}

function eliminarClientes(nCuenta) {
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 1 });
    let peticion = new XMLHttpRequest();
    peticion.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            let dat = JSON.parse(this.responseText, true);
            pintarRespuesta(dat);
        }
    }
    peticion.open('POST', "index1.php", true);
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send(`dato=${dato}&cont=clientes`);
}

function pedirDatos(nCuenta) {
    const datosCuenta = pedirDatCuenta(nCuenta);
    pintarDatos(datosCuenta, 'c_tbody');
    const clientes = pedirClientes(nCuenta);
    const dni1 = clientes['cu_dn1'];
    const datosDni1 = pedirDatosClientes(dni1);
    pintarDatos(datosDni1, 'tc_tbody');
    const dni2 = clientes['cu_dn2'];
    if (dni2 != '') {
        const datosDni2 = pedirDatosClientes(dni2);
        pintarDatos(datosDni2, 'tc_tbody');
    }

}

function pedirDatCuenta(nCuenta) {
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 6 });
    let peticion = new XMLHttpRequest();
    peticion.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            let dat = JSON.parse(this.responseText, true);
            return dat;
        }
    }
    peticion.open('POST', "index1.php", true);
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send(`dato=${dato}&cont=cuentas`);
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

function pedirDatosClientes(dni) {
    let dato = JSON.stringify({ dni: dni, opr: 2 });
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
    peticion.send(`dato=${dato}&cont=clientes`);
}

function pintarDatos(datos, tbody) {
    let tbody = document.getElementById("tbody");
    let tr = document.createElement("tr");
    for (let dato in datos) {
        textValor = document.createTextNode(decodeURIComponent(datos[dato]));
        tdValor = document.createElement("td");
        tdValor.appendChild(textValor);
        tr.appendChild(tdValor);
    }
    tbody.appendChild(tr);
}

/* function pintarR(estado, dato) {
    console.log(dato);
} */