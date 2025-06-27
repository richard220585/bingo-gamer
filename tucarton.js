function mostrarCarton(numeros) {
    const contenedor = document.getElementById('cartonVisual');
    contenedor.innerHTML = '';
    contenedor.classList.add("carton");

    if (!Array.isArray(numeros) || numeros.length !== 5) {
        contenedor.textContent = "Cartón no válido.";
        return;
    }

    numeros.forEach(fila => {
        if (Array.isArray(fila)) {
            fila.forEach(numero => {
                const casilla = document.createElement('div');
                casilla.textContent = numero;
                casilla.className = (numero === "X") ? "x" : "";
                contenedor.appendChild(casilla);
            });
        }
    });
}

/* Si quieres, aquí podrías añadir un listener para adaptar visualmente en móvil,
   pero como el CSS ya tiene responsive, no es obligatorio cambiar JS */
