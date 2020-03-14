let cValidos = Array();
document.addEventListener("readystatechange", () => {
    if (document.readyState == "interactive") {
        document.forms[0].nCuenta.addEventListener("focusout", validarCuenta, false);
        document.forms[0].dni1.addEventListener("focusout", validarDni, false);
        document.forms[0].dni2.addEventListener("focusout", validarDni, false);
        document.forms[0].importe.addEventListener("focusout", validarImporte, false);
        document.forms[0].registrar.addEventListener("click", registrar, false);
        document.forms[1].registrarCliente.addEventListener("click", registrarCliente, false);
        document.getElementById('registrarClientes').style.display = 'none';
    }
}, false);

function validarImporte(e) {
    let importe = document.forms[0].importe.value;
    if (importe < 1) {
        cValidos = cValidos.filter((v) => v != 'importe');
        document.getElementById('eimp').innerText = 'el importe tiene que ser mayor de 0'
    } else {
        document.getElementById('eimp').innerText = ' '
        if (cValidos.find((v) => v == e.target.name) === undefined) {
            cValidos.push(e.target.name);
        }

    }
}

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
        document.forms[0].dni1.disabled = false;
        document.getElementById('enc').innerText = ' ';
        if (cValidos.find((v) => v == 'nCuenta') === undefined) {
            cValidos.push('nCuenta');
        }

    } else {
        cValidos = cValidos.filter((v) => v != 'nCuenta');
        document.getElementById('enc').innerText = 'numero de cuenta incorrecto'
    }
}

function validarDni(e) {
    let dni = e.target.value;
    let dniOk = '';
    if (dni.length == 9) {
        try {
            var numDNI = parseInt(dni.substring(0, 8));
            var letras = ['T', 'R', 'W', 'A', 'G', 'M', 'Y', 'F', 'P', 'D', 'X', 'B', 'N', 'J', 'Z', 'S', 'Q', 'V', 'H', 'L', 'C', 'K', 'E', 'T'];
            var letraCorrecta = letras[numDNI % 23];
            if (dni.substring(8, 9).toUpperCase() == letraCorrecta) {
                dniOk = true;
            } else {
                dniOk = false;
            }
        } catch (e) {
            dniOk = false;
        }
    }

    if (dniOk) {
        document.getElementById('e' + e.target.name).innerText = ' ';

        verifAltaUser(dni).then(data => {

            if (data['cliente'] !== true) {
                document.getElementById('registrarClientes').style.display = "block";
                document.getElementById('e' + e.target.name).innerText = 'el cliente no existe';
            } else {
                document.getElementById('registrarClientes').style.display = "none";
                if (e.target.name == 'dni1') {
                    document.forms[0].importe.disabled = false;
                    document.forms[0].dni2.disabled = false;
                    if (cValidos.find((v) => v == 'dni1') === undefined) {
                        cValidos.push('dni1');
                    }


                } else if (e.target.name == 'dni2') {
                    if (e.target.value == document.forms[0].dni1.value && e.target.value.length > 0) {
                        cValidos = cValidos.filter((v) => v != 'dni2');
                        document.getElementById('edni2').innerText = 'error dni2 es igual que dni1';
                    } else {
                        if (cValidos.find((v) => v == e.target.name) === undefined) {
                            cValidos.push(e.target.name);
                        }
                        document.forms[0].importe.disabled = false;
                    }
                }
            }

        });


    } else {
        if (e.target.name == 'dni1') {
            document.forms[0].importe.disabled = true;
            document.forms[0].dni2.disabled = true;
        }
        cValidos = cValidos.filter((v) => v != e.target.name);
        document.getElementById('e' + e.target.name).innerText = 'Error dni incorrecto';
    }
}

function validarForm2(dni, nombre, dir, tel, email, fNaz, saldo) {
    return true;
}

function registrarCliente(e) {
    let dni = document.forms[1].dni.value;
    let nombre = document.forms[1].nom.value;
    let dir = document.forms[1].dir.value;
    let tel = document.forms[1].tel.value;
    let email = document.forms[1].email.value;
    let fNaz = document.forms[1].fNaz.value;
    let fActual = new Date();
    fActual = fActual.getFullYear() + "-" + fActual.getMonth() + "-" + fActual.getDate();
    if (validarForm2(dni, nombre, dir, tel, email, fNaz, saldo)) {
        let dato = JSON.stringify({ dni: dni, nombre: nombre, dir: dir, tel: tel, email: email, fNaz: fNaz, fAlta: fActual, numCuen: 0, saldo: 0, opr: 4 });
        let peticion = new XMLHttpRequest();
        peticion.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                let dat = JSON.parse(this.responseText, true);
                if (dat['cliente'] === true) {
                    alert('cliente registrado con exito');
                    document.forms[1].reset();
                    document.getElementById('registrarClientes').style.display = 'none';
                } else {
                    document.getElementById('errForm2').innerText = dat['cliente'];
                }
            }
        }
        peticion.open('POST', "index1.php", false);
        peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        peticion.send(`dato=${dato}&cont=clientes`);
    }
}

async function verifAltaUser(dni) {
    let dato = JSON.stringify({ dni: dni, opr: 3 });
    const location = 'localhost';
    const path = '/javascript/trabajo-appbancaria/index1.php';
    let datos = `dato=${dato}&cont=clientes`
    const settings = {
        method: 'POST',
        body: datos,
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    };
    try {
        const fetchResponse = await fetch(`http://${location}${path}`, settings);
        const data = await fetchResponse.json();
        return data;
    } catch (e) {
        return e;
    }
}

function registrar(e) {
    if ((cValidos.length === 3 && cValidos.find((v) => v == document.forms[0].dni2.value) === undefined) || cValidos.length === 4) {
        let nCuenta = document.forms[0].nCuenta.value;
        let dni1 = document.forms[0].dni1.value;
        let dni2 = document.forms[0].dni2.value;
        let importe = document.forms[0].importe.value;
        let mensaje = ` Se van a registrar los siguientes datos \n nº de cuenta = ${nCuenta}, \n dni1 = ${dni1}, \n dni2 = ${dni2}, \n importe = ${importe}.\n Estas conforme ?`
        let ok = confirm(mensaje);
        if (ok) {
            let dato = JSON.stringify({ nCuenta: nCuenta, dni1: dni1, dni2: dni2, importe: importe, opr: 1 });
            let peticion = new XMLHttpRequest();
            peticion.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    let dat = JSON.parse(this.responseText);
                    if (dat['cuenta']) {
                        alert('cuenta registrada correctamente');
                        incrementarNumCuen(dni1, importe);
                        if (cValidos.find((v) => v == 'dni2') !== undefined) {
                            incrementarNumCuen(dni2, importe);
                        }
                        registrarMovi(nCuenta, `apertura de cuenta ${nCuenta}`, importe);
                        document.forms[0].reset();
                    } else {
                        document.getElementById('errForm1').innerText = dat['cuenta'];
                    }
                }
            }
            peticion.open('POST', "index1.php", true);
            peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            peticion.send(`dato=${dato}&cont=cuentas`);
        }
    }

}

function incrementarNumCuen(dni, importe) {
    let dato = JSON.stringify({ dni: dni, importe: importe, opr: 5 });
    let peticion = new XMLHttpRequest();
    peticion.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let dat = JSON.parse(this.responseText, true);
            if (dat['cliente'] === true) {
                alert(`datos del cliente ${dni} actualizados`);
            } else {
                document.getElementById('errForm1').innerText = dat['cliente'];
            }
        }
    }
    peticion.open('POST', "index1.php", true);
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send(`dato=${dato}&cont=clientes`);
}

function registrarMovi(nCuenta, desc, importe) {
    let dato = JSON.stringify({ nCuenta: nCuenta, desc: desc, importe: importe, opr: 3 });
    let peticion = new XMLHttpRequest();
    peticion.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let dat = JSON.parse(this.responseText, true);
            if (dat['move'] === true) {
                alert(`movimiento ${desc} registrado`);
            } else {
                document.getElementById('errForm1').innerText = dat['move'];
            }
        }
    }
    peticion.open('POST', "index1.php", true);
    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    peticion.send(`dato=${dato}&cont=movimientos`);
}