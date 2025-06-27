<?php
// header.php
?>
<header>
  <a href="index.php">
    <img src="imagenes/miportada.png" alt="Logo Bingo" class="logo-img">
  </a>

  <div class="hamburger" onclick="toggleMenu()">☰</div>

  <nav class="menu">
    <div class="menu-item">
      <a href="compras.php">
        <img src="imagenes/carrito.png" alt="Compra" class="icon">
      </a>
      <a href="compras.php">
        <button class="menu-btn">Compra</button>
      </a>
    </div>

    <div class="menu-item dropdown">
      <a href="#" class="dropbtn" onclick="toggleDropdown(event)"> 
        <img src="imagenes/carton.png" alt="Cartones" class="icon">
        <button class="menu-btn">Cartones ▼</button>
      </a>
      <div class="dropdown-content">
        <a href="tucarton_del_dia.php">Tu Juego de Hoy</a>
        <a href="tucarton.php">Todos Tus Juegos</a>
      </div>
    </div>

    <div class="menu-item">
      <a href="testimonios.php">
        <img src="imagenes/testimonio.png" alt="Testimonio" class="icon">
      </a>
      <a href="testimonios.php">
        <button class="menu-btn">Testimonio</button>
      </a>
    </div>

    <div class="menu-item">
      <a href="soporte.php">
        <img src="imagenes/soporte.png" alt="Soporte" class="icon">
      </a>
      <a href="soporte.php">
        <button class="menu-btn">Soporte</button>
      </a>
    </div>
  </nav>
</header>

<style>
header {
  width: 100%;
  background: linear-gradient(to right, #fff5e6, #ffcc99);
  padding: 20px 30px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  position: sticky;
  top: 0;
  z-index: 1000;
  backdrop-filter: blur(6px);
  color: #5C3A00;
}

/* Animación swing para la imagen */
@keyframes swing {
  0%, 100% { transform: rotate(0deg); }
  25% { transform: rotate(5deg); }
  50% { transform: rotate(0deg); }
  75% { transform: rotate(-5deg); }
}

.logo-img {
  max-width: 200px;
  height: auto;
  animation: swing 3s ease-in-out infinite;
  transform-origin: center bottom;
}

.menu {
  display: flex;
  gap: 50px;
  justify-content: flex-end;
  align-items: center;
  flex-grow: 1;
}

.menu-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
}

.menu-item a {
  text-decoration: none;
  color: #003366;
}

.icon {
  max-width: 60px;
  height: auto;
  transition: transform 0.3s ease;
}

.icon:hover {
  transform: scale(1.1);
}

.menu-btn {
  margin-top: 8px;
  padding: 10px 20px;
  background: linear-gradient(to right, #005B99, #007ACC);
  color: white;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-size: 17px;
  font-weight: bold;
  transition: background 0.25s ease, transform 0.25s ease;
}

.menu-btn:hover {
  background: linear-gradient(to right, #004B80, #0066AA);
  transform: scale(1.05);
}

/* Dropdown */
.menu-item.dropdown .dropdown-content {
  display: none;
  position: absolute;
  background-color: #ffffff;
  color: #003366;
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  padding: 10px 20px;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  border-radius: 15px;
  min-width: 200px;
  font-weight: 600;
  z-index: 999;
  flex-direction: column;
  text-align: center;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.3s ease;
}

.menu-item.dropdown:hover .dropdown-content {
  display: flex;
  opacity: 1;
  pointer-events: auto;
}

.dropdown-content a {
  display: block;
  padding: 10px 0;
  color: #003366;
  text-decoration: none;
  border-radius: 8px;
  font-size: 16px;
  transition: background-color 0.2s ease;
}

.dropdown-content a:hover {
  background-color: #e6f0ff;
}

/* Hamburger */
.hamburger {
  display: none;
  font-size: 32px;
  color: #5C3A00;
  cursor: pointer;
  user-select: none;
  margin-left: 10px;
}

/* Responsive */
@media (max-width: 768px) {
  .hamburger {
    display: block;
  }

  .menu {
    display: none;
    flex-direction: column;
    gap: 15px;
    background-color: rgba(255, 204, 153, 0.95);
    position: absolute;
    top: 80px;
    left: 0;
    right: 0;
    max-width: 360px; /* ancho máximo para móvil */
    margin: 0 auto;    /* centrar el menú */
    padding: 20px 0;
    z-index: 1001;
    border-radius: 0 0 20px 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.25);
  }

  .menu.show {
    display: flex;
  }

  .menu-item {
    flex-direction: row;
    justify-content: flex-start;
    gap: 10px;
  }

  .menu-btn {
    margin-top: 0;
    font-size: 14px;
    padding: 8px 16px;
  }

  .menu-item.dropdown .dropdown-content {
    position: relative;
    background-color: transparent;
    box-shadow: none;
    padding: 0;
    top: 0;
    left: 0;
    transform: none;
    opacity: 1 !important;
    pointer-events: auto !important;
    display: none;
  }

  .menu-item.dropdown.show .dropdown-content {
    display: flex !important;
    flex-direction: column;
  }
}
</style>

<script>
function toggleMenu() {
  const menu = document.querySelector('.menu');
  menu.classList.toggle('show');
}

function toggleDropdown(event) {
  if(window.innerWidth <= 768){
    event.preventDefault();
    const dropdown = event.currentTarget.closest('.dropdown');
    dropdown.classList.toggle('show');
  }
}
</script>
