const bingoTableBody     = document.querySelector('#bingoTable tbody');
const numeroGrande       = document.getElementById('numeroGrande');
const ultimosNumerosDiv  = document.getElementById('ultimosNumeros');
const mensajeGanadorDiv  = document.getElementById('mensajeGanador');
const sonidoClick        = document.getElementById('sonidoClick');
const selectModoJuego    = document.getElementById('modoJuego');
const cartonEjemploDiv   = document.getElementById('cartonEjemplo');
const contadorJugadas    = document.getElementById('contadorJugadas');
const btnLimpiar         = document.getElementById('btnLimpiar');
const btnToggleDesdichado= document.getElementById('btnToggleDesdichado');

let seleccionados = new Set();
let ultimosNumeros = [];
let cartones = {};
let modoJuego = "";
let desdichadoEnabled = true;

const rangos = {
  B: [1, 15],
  I: [16, 30],
  N: [31, 45],
  G: [46, 60],
  O: [61, 75]
};

selectModoJuego.addEventListener('change', () => {
  modoJuego = selectModoJuego.value;
  const mensajes = {
    "cuatro_esquinas": "Modo 4 esquinas activado",
    "carton_lleno": "Modo Cartón Lleno activado",
    "letra_x": "Modo Letra X activado",
    "letra_l": "Modo Letra L activado",
    "letra_t": "Modo Letra T activado",
    "cruz_central": "Modo Cruz Central activado",
  };
  if (mensajes[modoJuego]) alert(mensajes[modoJuego]);
  mostrarCartonEjemplo();
  verificarGanador();
});

btnLimpiar.addEventListener('click', () => {
  document.querySelectorAll('#bingoTable td.selected')
    .forEach(td => td.classList.remove('selected'));
  seleccionados.clear();
  ultimosNumeros = [];
  actualizarUltimos();
  actualizarContador();
  numeroGrande.textContent = '--';
  mensajeGanadorDiv.textContent = '';
});

btnToggleDesdichado.addEventListener('click', () => {
  desdichadoEnabled = !desdichadoEnabled;
  btnToggleDesdichado.textContent = desdichadoEnabled
    ? 'Deshabilitar desdichado'
    : 'Habilitar desdichado';
});

function mostrarCartonEjemplo() {
  const posiciones = {
    cuatro_esquinas: [0, 4, 20, 24],
    carton_lleno: Array.from({ length: 25 }, (_, i) => i).filter(i => i !== 12),
    letra_x: [0, 4, 6, 8, 12, 16, 18, 20, 24],
    letra_l: [0, 5, 10, 15, 20, 21, 22, 23, 24],
    letra_t: [0, 1, 2, 3, 4, 12, 17, 22],
    cruz_central: [2, 7, 12, 17, 22]
  };

  const celdasMarcadas = posiciones[modoJuego] || [];
  const table = document.createElement('table');
  table.className = 'carton-ejemplo';
  let index = 0;
  for (let i = 0; i < 5; i++) {
    const tr = document.createElement('tr');
    for (let j = 0; j < 5; j++) {
      const td = document.createElement('td');
      if (index === 12) td.textContent = 'X';
      if (celdasMarcadas.includes(index)) td.classList.add('marcado');
      tr.appendChild(td);
      index++;
    }
    table.appendChild(tr);
  }
  cartonEjemploDiv.innerHTML = "<h4>Cartón objetivo:</h4>";
  cartonEjemploDiv.appendChild(table);
}

function crearTablero() {
  bingoTableBody.innerHTML = '';
  for (let fila = 0; fila < 15; fila++) {
    const tr = document.createElement('tr');
    ['B','I','N','G','O'].forEach(col => {
      const td = document.createElement('td');
      td.textContent = rangos[col][0] + fila;
      td.dataset.numero = td.textContent;
      tr.appendChild(td);
    });
    bingoTableBody.appendChild(tr);
  }
}

function actualizarUltimos() {
  ultimosNumerosDiv.innerHTML = '';
  ultimosNumeros.slice(-5).reverse().forEach((num, i) => {
    const d = document.createElement('div');
    d.classList.add('numero');
    d.textContent = num;
    if (i === 0) d.style.fontSize = '26px';
    ultimosNumerosDiv.appendChild(d);
  });
}

function actualizarContador() {
  const total = 75;
  const actuales = seleccionados.size;
  contadorJugadas.textContent = `${actuales} de ${total}`;
}

function reproducirSonido() {
  sonidoClick.currentTime = 0;
  sonidoClick.play();
}

function verificarGanador() {
  if (!modoJuego) return;
  const marcados = new Set([...seleccionados].map(n => n.toString()));
  let ganadores = [];

  for (const [id, numerosCarton] of Object.entries(cartones)) {
    const plano = Array.isArray(numerosCarton[0]) ? numerosCarton.flat() : numerosCarton;
    const c = plano.map((n, i) => (i === 12 ? 'X' : n.toString()));

    const condiciones = {
      carton_lleno: () => c.every(n => n === 'X' || marcados.has(n)),
      cuatro_esquinas: () => [0,4,20,24].every(i => marcados.has(c[i])),
      letra_x: () => [0, 4, 6, 8, 16, 18, 20, 24].every(i => marcados.has(c[i])) && c[12] === 'X',
      letra_l: () => [0, 5, 10, 15, 20, 21, 22, 23, 24].every(i => marcados.has(c[i])),
      letra_t: () => [0, 1, 2, 3, 4, 17, 22].every(i => marcados.has(c[i])) && c[12] === 'X',
      cruz_central: () => [2, 7, 17, 22].every(i => marcados.has(c[i])) && c[12] === 'X'
    };

    if (condiciones[modoJuego]?.()) ganadores.push(id);
  }

  mensajeGanadorDiv.textContent = ganadores.length
    ? `🎉 ¡Ganador(es)! Cartón(es): ${ganadores.join(', ')}`
    : '';
}

function manejarClickNumero(e) {
  const td = e.target;
  if (!td || td.tagName !== 'TD') return;
  if (!modoJuego) {
    alert("Debes seleccionar un modo de juego antes de comenzar");
    return;
  }

  const num = td.dataset.numero;
  if (seleccionados.has(num)) {
    seleccionados.delete(num);
    ultimosNumeros = ultimosNumeros.filter(n => n !== num);
    td.classList.remove('selected');
  } else {
    seleccionados.add(num);
    ultimosNumeros.push(num);
    td.classList.add('selected');
    numeroGrande.textContent = num;
    numeroGrande.classList.add('salida');
    setTimeout(() => numeroGrande.classList.remove('salida'), 500);
    reproducirSonido();
  }

  actualizarUltimos();
  actualizarContador();

  if (desdichadoEnabled && seleccionados.size === 14) {
    const seleccionadosNum = Array.from(seleccionados).map(n => parseInt(n));
    const desdichados = [];

    for (const [id, numeros] of Object.entries(cartones)) {
      const numsPlano = Array.isArray(numeros[0])
        ? numeros.flat().filter(n => n !== 'X').map(n => parseInt(n))
        : numeros.filter(n => n !== 'X').map(n => parseInt(n));
      const tieneAlMenosUno = seleccionadosNum.some(n => numsPlano.includes(n));
      if (!tieneAlMenosUno) {
        desdichados.push(id);
      }
    }

    if (desdichados.length) {
      alert(`Desdichado has ganado!\nCartones: ${desdichados.join(', ')}`);
    }
  }

  verificarGanador();
}

async function cargarCartones() {
  try {
    const res = await fetch('cartones_fijos.json');
    cartones = await res.json();
  } catch (e) {
    console.error('Error cargando cartones_fijos.json:', e);
  }
}

document.addEventListener('DOMContentLoaded', async () => {
  crearTablero();
  await cargarCartones();
  bingoTableBody.addEventListener('click', manejarClickNumero);
  actualizarUltimos();
  actualizarContador();
  numeroGrande.textContent = '--';
  mostrarCartonEjemplo();
});
