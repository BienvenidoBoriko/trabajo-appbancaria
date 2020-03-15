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
    return petGenerico(dato, cont);
}

async function verSaldoCuenta(nCuenta) {
    let nCuentaOk = true;
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 4 });
    let cont = "cuentas";
    return await petGenerico(dato, cont);

}

function auxiValidarCuenta(nCuentaOk) {
    console.log(nCuentaOk);
    if (nCuentaOk == true) {
        document.getElementById('enc').innerText = ' ';
        if (cValidos.find((v) => v == 'nCuenta') === undefined) {
            cValidos.push('nCuenta');
        }
    } else {
        cValidos = cValidos.filter((v) => v != 'nCuenta');
        if (nCuentaOk == false) {
            document.getElementById('enc').innerText = 'numero de cuenta incorrecto';
        } else if (nCuentaOk == -1) {
            document.getElementById('enc').innerText = 'La cuenta no existe';
        } else if (nCuentaOk == -3) {
            document.getElementById('enc').innerText = 'La cuenta no esta vacia';
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
            nCuentaOk = validarNumCuenta(e.target.value).then((datos) => {
                if (datos['cuenta']) {
                    nCuentaOk = verSaldoCuenta(e.target.value).then((datos) => {
                        if (datos['saldo'] == -1) {
                            auxiValidarCuenta(-1);
                        } else if (datos['cuenta'] == false) {
                            auxiValidarCuenta(false);
                        } else if ((typeof datos['cuenta']) === 'Array') {
                            auxiValidarCuenta(-3);
                        } else {
                            auxiValidarCuenta(true);
                        }
                    });
                } else if (datos['cuenta'] == false) {
                    auxiValidarCuenta(-1);
                } else if (datos['cuenta'] == -1) {
                    auxiValidarCuenta(false);
                }
            });
        } else {
            auxiValidarCuenta(false);
        }

    } else {
        auxiValidarCuenta(false);
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
    if (validar()) {
        let nCuenta = document.forms[0].nCuenta.value;

        let mensaje = ` Se va a eliminar los registros correspondientes a la \ncuenta = ${nCuenta}, \n Estas conforme ?`
        let ok = confirm(mensaje);
        if (ok) {
            eliminarCuenta(nCuenta).then((datos) => {
                if (datos['cierre']) {
                    eliminarClientes(nCuenta).then((cliente) => {
                        if (cliente['el_client']) {
                            eliminarMov(nCuenta).then((mov) => {
                                if (mov['el_mov']) {
                                    alert('datos eliminados con exito');
                                } else {
                                    alert('Hubo un error al eliminar los movimientos');
                                }
                            })

                        } else {
                            alert('Hubo un error al eliminar los clientes');
                        }
                    })

                } else {
                    alert('Hubo un error al eliminar la cuenta');
                }
            });

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
    pedirDatCuenta(nCuenta).then((datos) => pintarDatos(datos['datos'], 'c_tbody'))
    pedirClientes(nCuenta).then((clientes) => {
        const dni1 = clientes['titulares'][0]['cu_dn1'];
        const dni2 = clientes['titulares'][0]['cu_dn2'];
        pedirDatosClientes(dni1).then((datosDni1) => {
            pintarDatos(datosDni1['datos'], 'tc_tbody');
        });
        if (dni2 != '') {
            pedirDatosClientes(dni2).then((datosDni2) => {
                pintarDatos(datosDni2['datos'], 'tc_tbody');
            });
        }
    });
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