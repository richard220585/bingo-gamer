let contador = 0;
let total = 0;
let cartonesSeleccionados = [];
let labelPago = document.getElementById("labelPago");
let numeroSeleccionado = null;
let precioPorCarton = 40; // Valor por defecto, se sobrescribe luego

// Cargar precio dinámico desde JSON
fetch('precio_carton.json')
    .then(response => response.json())
    .then(data => {
        if (data.precio) {
            precioPorCarton = parseFloat(data.precio);
        }
    })
    .catch(() => {
        console.warn("No se pudo cargar precio_carton.json, usando 40 BS por defecto.");
    });

function generarCarton(numero) {
    numeroSeleccionado = numero;

    let tituloModal = document.getElementById('cartonTitulo');
    tituloModal.textContent = `Número del Cartón Seleccionado: ${numero}`;

    let cartonDiv = document.getElementById('carton');
    cartonDiv.innerHTML = '';

    fetch(`obtener_carton.php?numero=${numero}`)
        .then(response => response.json())
        .then(carton => {
            cartonDiv.innerHTML = '';
            carton.forEach(fila => {
                fila.forEach(num => {
                    let div = document.createElement('div');
                    div.textContent = num;
                    div.className = (num === "X") ? "x" : "";
                    cartonDiv.appendChild(div);
                });
            });

            document.getElementById('cartonModal').style.display = 'flex';
            labelPago.style.display = 'none';
        })
        .catch(() => {
            cartonDiv.textContent = "Error al cargar el cartón.";
        });
}

function cerrarModal() {
    document.getElementById('cartonModal').style.display = 'none';
    labelPago.style.display = 'block';
}

function seleccionarCarton() {
    if (numeroSeleccionado !== null) {
        if (!cartonesSeleccionados.includes(numeroSeleccionado)) {
            cartonesSeleccionados.push(numeroSeleccionado);
            contador++;
            total = contador * precioPorCarton;

            localStorage.setItem('contador', contador);
            localStorage.setItem('total', total);
            localStorage.setItem('cartonesSeleccionados', JSON.stringify(cartonesSeleccionados));

            actualizarLabelPago();
        }

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
