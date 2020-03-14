let cValidos = Array();
document.addEventListener("readystatechange", () => {
    if (document.readyState == "interactive") {
        document.forms[0].nCuenta.addEventListener("focusout", validarCuenta, false);
        document.forms[0].ver.addEventListener("click", realizar, false);
        document.getElementById('l_movimientos').style.display = 'none';
    }
}, false);

function validarFechas(fecha1, fecha2) {
    let fechasOk = '';
    fecha1 = new Date(fecha1);
    fecha2 = new Date(fecha2);
    if (fecha2.getTime() > fecha1.getTime()) {
        fechasOk = true;
    }

    return fechasOk;
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
    if (document.forms[0].fechaP.value == '') {
        ok = false;
        document.getElementById('efp').innerText = 'Error fecha incorreta';
    } else {
        document.getElementById('efp').innerText = '';
    }

    if (document.forms[0].fechaU.value == '') {
        ok = false;
        document.getElementById('efu').innerText = 'Error fecha incorreta';
    } else {
        if (validarFechas(document.forms[0].fechaP.value, document.forms[0].fechaU.value) === true) {
            document.getElementById('efu').innerText = '';
        } else {
            ok = false;
            document.getElementById('efu').innerText = 'Error fecha2 es menor que fecha1';
        }
    }

    if (cValidos.length !== 1) {
        ok = false;
    }
    return ok;
}

function realizar(e) {
    if (validar()) {
        let nCuenta = document.forms[0].nCuenta.value;
        let fechaP = document.forms[0].fechaP.value;
        let fechaU = document.forms[0].fechaU.value;
        let mensaje = ` Se van a pedir los registros que correspondan con los siguientes datos \n nº de cuenta = ${nCuenta}, \n fechaP = ${fechaP}, \n fechaU = ${fechaU}, \n Estas conforme ?`
        let ok = confirm(mensaje);
        if (ok) {
            let dato = JSON.stringify({ nCuenta: nCuenta, fechaP: fechaP, fechaU: fechaU, opr: 1 });
            let peticion = new XMLHttpRequest();
            peticion.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    let dat = JSON.parse(this.responseText);
                    if (dat['mensaje'] === undefined) {
                        vaciarTabla();
                        document.getElementById('l_movimientos').style.display = 'block';
                        pintarRespuesta(dat);
                    } else {
                        document.getElementById('moverror').innerText = dat['mensaje'];
                    }

                }
            }

            peticion.open('POST', "index1.php", true);
            peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            peticion.send(`dato=${dato}&cont=movimientos`);
        }
    }

}

function pintarRespuesta(datos) {
    let tbody = document.getElementById("lm_tbody");
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

/* function pintarR(estado, dato) {
    console.log(dato);
} */