const bingoTableBody     = document.querySelector('#bingoTable tbody');
const numeroGrande       = document.getElementById('numeroGrande');
const ultimosNumerosDiv  = document.getElementById('ultimosNumeros');
const mensajeGanadorDiv  = document.getElementById('mensajeGanador');
const sonidoClick        = document.getElementById('sonidoClick');
const selectModoJuego    = document.getElementById('modoJuego');
const cartonEjemploDiv   = document.getElementById('cartonEjemplo');

// ===> REFERENCIAS A BOTONES (añadidos)
const btnLimpiar             = document.getElementById('btnLimpiar');
const btnToggleDesdichado    = document.getElementById('btnToggleDesdichado');

let seleccionados = new Set();
let ultimosNumeros = [];
let cartones = {};
let modoJuego = "";
let desdichadoEnabled = true; // controla si mostramos el alert “desdichado”

// Rangos por columna B-I-N-G-O
const rangos = {
  B: [1, 15],
  I: [16, 30],
  N: [31, 45],
  G: [46, 60],
  O: [61, 75]
};

selectModoJuego.addEventListener('change', () => {
  modoJuego = selectModoJuego.value;
  if (modoJuego === "cuatro_esquinas") alert("Modo 4 esquinas activado");
  else if (modoJuego === "carton_lleno") alert("Modo Cartón Lleno activado");
  mostrarCartonEjemplo();
});

// ===> EVENT LISTENER “LIMPIAR”
btnLimpiar.addEventListener('click', () => {
  document.querySelectorAll('#bingoTable td.selected')
    .forEach(td => td.classList.remove('selected'));
  seleccionados.clear();
  ultimosNumeros = [];
  actualizarUltimos();
  numeroGrande.textContent = '--';
  mensajeGanadorDiv.textContent = '';
});

// ===> EVENT LISTENER “TOGGLE DESDICHADO”
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
  ultimosNumeros.slice(-5).reverse().forEach(num => {
    const d = document.createElement('div');
    d.classList.add('numero');
    d.textContent = num;
    ultimosNumerosDiv.appendChild(d);
  });
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
    const c = numerosCarton.map(n => n.toString());
    if (modoJuego === "carton_lleno") {
      const sinX = c.filter(n => n !== 'X');
      if (sinX.every(n => marcados.has(n))) ganadores.push(id);
    }
    if (modoJuego === "cuatro_esquinas") {
      const esquinas = [0,4,20,24].map(i => c[i]);
      if (esquinas.every(n => marcados.has(n))) ganadores.push(id);
    }
  }
  mensajeGanadorDiv.textContent = ganadores.length
    ? `🎉 ¡Ganador(es)! Cartón(es): ${ganadores.join(', ')}`
    : '';
}

function mostrarCartonModal(id) {
  if (!cartones[id]) {
    alert("Cartón no encontrado");
    return;
  }
  const nums = cartones[id].map(n => n.toString());
  const modal = document.getElementById('modalContent');
  modal.innerHTML = '';
  const t = document.createElement('table');
  t.className = 'carton-ejemplo';
  let idx = 0;
  for (let i = 0; i < 5; i++) {
    const tr = document.createElement('tr');
    for (let j = 0; j < 5; j++) {
      const td = document.createElement('td');
      const v = nums[idx++];
      td.textContent = v;
      if (v==='X' || (seleccionados.has(v) && v!=='X')) td.classList.add('marcado');
      tr.appendChild(td);
    }
    t.appendChild(tr);
  }
  modal.appendChild(t);
  document.getElementById('modal').style.display = 'block';
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

  if (desdichadoEnabled && seleccionados.size === 10) {
    const arr = Array.from(seleccionados).map(n => n.toString());
    const sin = [];
    for (const [id, nums] of Object.entries(cartones)) {
      const c = nums.map(n => n.toString());
      if (!arr.some(n => c.includes(n))) sin.push(id);
    }
    if (sin.length) alert(`desdichado has ganado !\n${sin.join(', ')}`);
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
  crearTablero();          // ESTO DIBUJA DEL 1 AL 75
  await cargarCartones();
  bingoTableBody.addEventListener('click', manejarClickNumero);
  actualizarUltimos();
  numeroGrande.textContent = '--';
});
