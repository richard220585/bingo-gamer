function mostrarCarton(numeros) {
    const contenedor = document.getElementById('cartonVisual');
    contenedor.innerHTML = '';

    numeros.forEach((numero, index) => {
        const casilla = document.createElement('div');
        casilla.textContent = numero;
        contenedor.appendChild(casilla);
    });
}
