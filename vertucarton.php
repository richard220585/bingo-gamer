<?php
// Ejemplo básico de números con botones para mostrar cartón
$cartones_comprados = [4, 5, 12]; // Ejemplo, tus números vienen de la BD
?>

<!DOCTYPE html>
<html>
<head>
  <title>Ver tu cartón</title>
</head>
<body>

<?php foreach ($cartones_comprados as $numero): ?>
  <button class="ver-carton" data-numero="<?= $numero ?>">Ver Cartón <?= $numero ?></button>
<?php endforeach; ?>

<div id="modal-carton" style="display:none;">
  <div id="contenido-carton"></div>
  <button onclick="cerrarModal()">Cerrar</button>
</div>

<script>
  // Función para cerrar modal
  function cerrarModal(){
    document.getElementById('modal-carton').style.display = 'none';
  }

  // Escuchar click en botones para mostrar cartón
  document.querySelectorAll('.ver-carton').forEach(btn => {
    btn.addEventListener('click', () => {
      const numero = btn.getAttribute('data-numero');
      fetch(`obtener_carton.php?numero=${numero}`)
        .then(res => res.json())
        .then(carton => {
          if(carton.length === 0){
            alert('Cartón no encontrado');
            return;
          }
          let html = '<table border="1" style="border-collapse:collapse; text-align:center;">';
          html += '<tr><th>B</th><th>I</th><th>N</th><th>G</th><th>O</th></tr>';
          for(let fila=0; fila<5; fila++){
            html += '<tr>';
            for(let col=0; col<5; col++){
              const index = fila*5 + col;
              let valor = carton[index];
              html += `<td style="padding:10px;">${valor}</td>`;
            }
            html += '</tr>';
          }
          html += '</table>';
          document.getElementById('contenido-carton').innerHTML = html;
          document.getElementById('modal-carton').style.display = 'block';
        })
        .catch(() => alert('Error cargando cartón'));
    });
  });
</script>

</body>
</html>
