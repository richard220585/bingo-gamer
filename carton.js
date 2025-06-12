let contador = 0;
let total = 0;
let cartonesSeleccionados = [];

let labelPago = document.getElementById("labelPago");
let numeroSeleccionado = null;

function generarCarton(numero) {
    numeroSeleccionado = numero;

    let tituloModal = document.getElementById('cartonTitulo');
    tituloModal.textContent = `Número del Cartón Seleccionado: ${numeroSeleccionado}`;

    let cartonDiv = document.getElementById('carton');
    cartonDiv.innerHTML = '';

    // Llamar al PHP para obtener el cartón fijo
    fetch(`obtener_carton.php?numero=${numeroSeleccionado}`)
    .then(response => response.json())
    .then(carton => {
        // Mostrar el cartón
        carton.forEach((num, i) => {
            let div = document.createElement('div');
            div.textContent = num;
            div.style.textAlign = 'center';
            div.style.width = '60px';
            div.style.height = '60px';
            div.style.border = '1px solid #007bb2';

            if(num === "X") {
                div.style.backgroundColor = "#ffeb3b";
                div.style.color = "white";
                div.style.fontWeight = "bold";
            }

            cartonDiv.appendChild(div);
        });

        document.getElementById('cartonModal').style.display = 'flex';
    })
    .catch(() => {
        cartonDiv.textContent = "Error al cargar el cartón.";
        document.getElementById('cartonModal').style.display = 'flex';
    });
}

function seleccionarCarton() {
    if (numeroSeleccionado !== null) {
        contador++;
        total = contador * 40;

        if (!cartonesSeleccionados.includes(numeroSeleccionado)) {
            cartonesSeleccionados.push(numeroSeleccionado);
        }

        localStorage.setItem('contador', contador);
        localStorage.setItem('total', total);
        localStorage.setItem('cartonesSeleccionados', JSON.stringify(cartonesSeleccionados));

        actualizarLabelPago();
        cerrarModal();
    }
}

function actualizarLabelPago() {
    labelPago.textContent = `Ir a pagar (${contador} cartones) - Total: ${total} BS`;
    labelPago.style.cursor = "pointer";
}

function irAPagar() {
    const cartones = JSON.stringify(cartonesSeleccionados);
    window.location.href = `pagocarton.php?contador=${contador}&total=${total}&cartones=${encodeURIComponent(cartones)}`;
}

function cerrarModal() {
    document.getElementById('cartonModal').style.display = 'none';
}
