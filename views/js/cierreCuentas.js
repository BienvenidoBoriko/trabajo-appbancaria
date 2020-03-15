let cValidos = Array();
document.addEventListener("readystatechange", () => {
    if (document.readyState == "interactive") {
        document.forms[0].nCuenta.addEventListener("focusout", validarCuenta, false);
        document.forms[0].cerrar.addEventListener("click", realizar, false);
    }
}, false);


function validarNumCuenta(nCuenta) {
    let nCuentaOk = true;
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 3 });
    let cont = "cuentas";
    petGenerico(dato, cont).then((datos) => {
        console.log(datos)
        if (datos['cuenta']) {
            nCuentaOk = true;
        } else if (datos['cuenta'] == false) {
            nCuentaOk = -1;
        } else if (datos['cuenta'] == -1) {
            nCuentaOk = -2;
        }
    });
    return nCuentaOk;
}

function verSaldoCuenta(nCuenta) {
    let nCuentaOk = true;
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 4 });
    let cont = "cuentas";
    petGenerico(dato, cont).then((datos) => {
        console.log(datos)
        if (datos['saldo'] == 0) {
            nCuentaOk = -3;
        } else if (datos['saldo'] === false) {
            nCuentaOk = false;
        } else {
            nCuentaOk = true;
        }
    });
    return nCuentaOk;
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
        pedirDatos(e.target.value);
    } else {
        cValidos = cValidos.filter((v) => v != 'nCuenta');
        if (nCuentaOk == false || nCuentaOk == -2) {
            document.getElementById('enc').innerText = 'numero de cuenta incorrecto';
        } else if (nCuentaOk == -1) {
            document.getElementById('enc').innerText = 'La cuenta no existe';
        } else if (nCuentaOk == -3) {
            document.getElementById('enc').innerText = 'La cuenta no esta vacia';
        } else {
            document.getElementById('enc').innerText = 'nose';
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

async function petGenerico(dato, cont) {
    const location = "localhost";
    const path = "/javascript/trabajo-appbancaria/index1.php";
    let datos = `dato=${dato}&cont=${cont}`;
    const settings = {
        method: "POST",
        body: datos,
        headers: {
            Accept: "application/json",
            "Content-Type": "application/x-www-form-urlencoded"
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

function eliminarCuenta(nCuenta) {
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 2 });
    let cont = "cuentas";
    return petGenerico(dato, cont);
}

function eliminarMov(nCuenta) {
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 2 });
    let cont = "movimientos";
    return petGenerico(dato, cont);
}

function eliminarClientes(nCuenta) {
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 1 });
    let cont = "clientes";
    return petGenerico(dato, cont);
}

function pedirDatos(nCuenta) {
    console.log('llamado')
    pedirDatCuenta(nCuenta).then((datos) => pintarDatos(datos['datos'], 'c_tbody'))
    pedirClientes(nCuenta).then((clientes) => {
        const dni1 = clientes['titulares'][0]['cu_dn1'];
        const dni2 = clientes['titulares'][0]['cu_dn2'];
        pedirDatosClientes(dni1).then((datosDni1) => {
            console.log(datosDni1)
            pintarDatos(datosDni1['datos'], 'tc_tbody');
        });
        if (dni2 != '') {
            pedirDatosClientes(dni2).then((datosDni2) => {
                console.log(datosDni2)
                pintarDatos(datosDni2['datos'], 'tc_tbody');
            });
        }
    });
    //const datosDni1 = pedirDatosClientes(dni1);
    //pintarDatos(datosDni1, 'tc_tbody');
    //const dni2 = clientes['cu_dn2'];
    /* if (dni2 != '') {
        const datosDni2 = pedirDatosClientes(dni2);
        pintarDatos(datosDni2, 'tc_tbody');
    }
 */
}

function pedirDatCuenta(nCuenta) {
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 6 });
    let cont = "cuentas";
    return petGenerico(dato, cont);
}

function pedirClientes(nCuenta) {
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 5 });
    let cont = "cuentas";
    return petGenerico(dato, cont);
}

function pedirDatosClientes(dni) {
    let dato = JSON.stringify({ dni: dni, opr: 2 });
    let cont = "clientes";
    return petGenerico(dato, cont);
}

function pintarDatos(datos, tbody) {
    tbody = document.getElementById(tbody);
    for (let fila in datos) {
        let tr = document.createElement("tr");
        for (let dato in datos[fila]) {
            textValor = document.createTextNode(decodeURIComponent(datos[fila][dato]));
            tdValor = document.createElement("td");
            tdValor.appendChild(textValor);
            tr.appendChild(tdValor);
        }
        tbody.appendChild(tr);
    }
}

function vaciarTabla() {
    let tbody = document.getElementById("lm_tbody");
    if (tbody.hasChildNodes()) {
        while (tbody.childNodes.length >= 1) {
            tbody.removeChild(tbody.firstChild);
        }
    }
}