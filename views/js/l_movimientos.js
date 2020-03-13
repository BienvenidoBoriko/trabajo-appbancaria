let cValidos = Array();
document.addEventListener("readystatechange", () => {
    if (document.readyState == "interactive") {
        document.forms[0].ncuenta.addEventListener("focusout", validarCuenta, false);
        document.forms[0].ver.addEventListener("click", realizar, false);
    }
}, false);

function validarFechas(fecha1, fecha2) {
    fechasOk = false;
    fecha1 = new Date(fecha1);
    fecha2 = new Date(fecha2);
    if (fecha2.getMilliseconds() > fecha1.getMilliseconds()) {
        fechasOk = -1;
    } else {
        fechasOk = true;
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
        if (validarFechas(document.forms[0].fechaP.value, document.forms[0].fechaU.value)) {
            document.getElementById('efu').innerText = '';
        } else {
            ok = false;
            document.getElementById('efu').innerText = 'Error fecha2 es mayor que fecha1';
        }
    }
    if (cValidos.length !== 1) {
        ok = false;
    }
    return ok;
}

function realizar(e) {
    console.log('click');
    if (validar()) {
        let nCuenta = document.forms[0].nCuenta.value;
        let fechaP = document.forms[0].fechaP.value;
        let fechaU = document.forms[0].fechaU.value;
        let mensaje = ` Se van a pedir los registros que correspondan con los siguientes datos \n nº de cuenta = ${nCuenta}, \n fechaP = ${fechaP}, \n fechaU = ${fechaU}, \n Estas conforme ?`
        let ok = confirm(mensaje);
        if (ok) {
            let dato = JSON.stringify({ nCuenta: nCuenta, fechaP: fechaP, fechaU: fechaU, cont: 'movimientos', opr: 1 });
            let peticion = new XMLHttpRequest();
            peticion.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    let dat = JSON.parse(this.responseText);
                    pintarRespuesta(dat);
                }
            }

            peticion.open('POST', "index1.php", true);
            peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            peticion.send(`dato=${dato}`);
        }
    }

}

function pintarRespuesta(datos) {

    let tbody = document.getElementById("lm_tbody");
    let tabla = new Array();
    for (let dato in datos) {
        let tr = document.createElement("tr");
        textIndice = document.createTextNode(decodeURIComponent(dato));
        tdIndice = document.createElement("td");
        tdIndice.appendChild(textIndice);
        tr.appendChild(tdIndice);
        textValor = document.createTextNode(decodeURIComponent(datos[dato]));
        tdValor = document.createElement("td");
        tdValor.appendChild(textValor);
        tr.appendChild(tdValor);
        tbody.appendChild(tr);
    }
}

/* function pintarR(estado, dato) {
    console.log(dato);
} */