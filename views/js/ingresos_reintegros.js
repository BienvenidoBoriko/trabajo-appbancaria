let cValidos = Array();
document.addEventListener("readystatechange", () => {
    if (document.readyState == "interactive") {
        document.forms[0].nCuenta.addEventListener("focusout", validarCuenta, false);
        document.forms[0].realizar.addEventListener("click", realizar, false);
    }
}, false);

function validarNumCuenta(nCuenta) {
    let nCuentaOk = true;
    let dato = JSON.stringify({ nCuenta: nCuenta, opr: 3 });
    let cont = "cuentas";
    return petGenerico(dato, cont);
}

function validarCuenta(e) {
    let nCuentaOk = true;
    let nCuenta = (e.target.value + "").split("").map(x => Number(x));
    if (nCuenta.length === 10) {
        let cpNcuenta = [...nCuenta];
        cpNcuenta.pop();
        if (
            cpNcuenta.reduce((acu, valor) => acu + valor) % 9 ===
            nCuenta[nCuenta.length - 1]
        ) {
            nCuentaOk = true;
        } else {
            nCuentaOk = false;
        }
    } else {
        nCuentaOk = false;
    }

    if (nCuentaOk) {
        validarNumCuenta(e.target.value).then((datos) => {
            if (datos['cuenta']) {
                document.getElementById("enc").innerText = " ";
                if (cValidos.find(v => v == "nCuenta") === undefined) {
                    cValidos.push("nCuenta");
                }
            } else {
                cValidos = cValidos.filter(v => v != "nCuenta");
                document.getElementById("enc").innerText = "Error la cuenta no existe";
            }
        });
    } else {
        cValidos = cValidos.filter(v => v != "nCuenta");
        console.log(cValidos);
        document.getElementById("enc").innerText = "numero de cuenta incorrecto";
    }
}

function validar() {
    let ok = true;
    let importe = document.forms[0].importe.value;
    if (document.forms[0].operacion.value == "") {
        ok = false;
        document.getElementById("eopr").innerText =
            "Error tienes que seleccionar una operacion";
    } else {
        document.getElementById("eopr").innerText = "";
    }

    if (document.forms[0].desc.value !== "") {
        if (document.forms[0].desc.value.length < 5) {
            document.getElementById("edesc").innerText = "Error longitud mayor de 5";
        } else {
            document.getElementById("edesc").innerText = "";
        }
    } else {
        document.getElementById("edesc").innerText =
            "Error desc no puede esta vacio";
        ok = false;
    }

    if (importe !== "") {
        importe = parseInt(importe);
        if (importe < 1) {
            document.getElementById("eimp").innerText =
                "Error importe tiene que ser mayor de 1";
        } else {
            document.getElementById("eimp").innerText = "";
        }
    } else {
        document.getElementById("eimp").innerText =
            "Error importe no puede esta vacio";
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
        let mensaje = ` Se va ha realizar la operacion ${
      nCuenta == 1 ? "reintegro" : "ingreso"
    } \n con los siguientes datos 
        nº de cuenta = ${nCuenta}, \n operacion = ${
      nCuenta == 1 ? "reintegro" : "ingreso"
    }, importe = ${importe}, descripcion ${desc}\n \n Estas conforme ?`;
        let ok = confirm(mensaje);
        if (ok) {
            modSaldoCuenta(nCuenta, importe, operacion).then(datos => {
                console.log(datos);
                if (datos["cuenta"] == true) {
                    alert("se ha modificado el saldo de la cuenta");
                    pedirClientes(nCuenta).then(clientes => {
                        console.log(clientes["titulares"]);
                        if (clientes["titulares"] !== undefined) {
                            alert("titulares recibidos");
                            modSaldoCliente(
                                clientes["titulares"][0]["cu_dn1"],
                                importe,
                                operacion
                            ).then(scliente1 => {
                                console.log(scliente1);
                                if (scliente1["cliente"]) {
                                    alert(
                                        `saldo del cliente ${clientes["titulares"][0]["cu_dn1"]} modificado`
                                    );
                                    if (clientes["titulares"][0]["cu_dn2"] !== undefined) {
                                        modSaldoCliente(
                                            clientes["titulares"][0]["cu_dn2"],
                                            importe,
                                            operacion
                                        ).then(scliente2 => {
                                            if (scliente2) {
                                                alert(
                                                    `saldo del cliente ${clientes["titulares"][0]["cu_dn2"]} modificado`
                                                );
                                                registrarMovi(nCuenta, desc, importe).then(mov => {
                                                    console.log(mov);
                                                    if (mov["move"] === true) {
                                                        alert("movimiento registrado con exito");
                                                    }
                                                });
                                            } else {}
                                        });
                                    }
                                }
                            });
                        } else {
                            alert("hubo un error al obtener los titulares");
                        }
                    });
                } else {
                    alert("hubo un error al modificar el saldo en la cuenta");
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

async function pedirClientes(nCuenta) {
    let dato = JSON.stringify({
        nCuenta: nCuenta,
        opr: 5
    });
    let cont = "cuentas";
    return await petGenerico(dato, cont);
}

async function modSaldoCuenta(nCuenta, importe, operacion) {
    let dato = JSON.stringify({
        nCuenta: nCuenta,
        importe: importe,
        operacion: operacion,
        opr: 7
    });
    let cont = "cuentas";
    return await petGenerico(dato, cont);
}

async function modSaldoCliente(dni, importe, operacion) {
    let dato = JSON.stringify({
        dni: dni,
        importe: importe,
        operacion: operacion,
        opr: 5
    });
    let cont = "clientes";
    return await petGenerico(dato, cont);
}

async function registrarMovi(nCuenta, desc, importe) {
    let dato = JSON.stringify({
        nCuenta: nCuenta,
        desc: desc,
        importe: importe,
        opr: 3
    });
    let cont = "movimientos";
    return await petGenerico(dato, cont);
}

/* function pintarR(estado, dato) {
    console.log(dato);
} */