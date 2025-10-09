  <?php require_once __DIR__ . '/../includes/header.php';
        require_once __DIR__ . '/../config/config.php';

        // Ahora podés usar $pdo para consultas
        $stmt = $pdo->query("SELECT * FROM secciones WHERE pagina = 'inicio'");
        $inicio = $stmt->fetchAll();

        $stmt = $pdo->query("SELECT * FROM secciones WHERE pagina = 'propuesta'");
        $propuesta = $stmt->fetchAll();

        $stmt = $pdo->query("SELECT * FROM secciones WHERE pagina = 'galeria'");
        $galeria = $stmt->fetchAll();
    
  ?>
  <section class="hero">
    <?php foreach ($inicio as $indice): ?>
    <div class="hero-text">
      <h1><?php echo htmlspecialchars($indice['titulo']); ?></h1>
      <p><?php echo htmlspecialchars($indice['contenido']); ?></p>
      <a href="#contacto" class="btn">Conocenos</a>
    </div>
    <?php endforeach; ?>
  </section>

  <section id="nosotros" class="section">
    <h2>¿Quiénes somos?</h2>
    <p>El Colegio Los Horneros ofrece una educación basada en el respeto, el juego, el trabajo colaborativo y el aprendizaje significativo.</p>
  </section>
   
  <section id="propuesta" class="section light">
    <?php foreach ($propuesta as $indice): ?>
    <h2><?php echo htmlspecialchars($indice['titulo']); ?></h2>
    <ul style="list-style:none">
      <?php $descripcion=  $indice['contenido'];
      $lineas = explode('.', $descripcion);
      foreach ($lineas as $linea) {
        $linea =trim($linea);
        if(!empty($linea)){
          echo  "<li>".htmlspecialchars($linea)."</li>";
        }
      }
      ?>
    </ul>
    <?php endforeach; ?>
  </section>

  <section id="galeria" class="section">
    <h2>Galería</h2>
    <div class="gallery">
    <?php foreach ($galeria as $indice): ?>
        <div>
            <!-- <img src="./assets/img/<?php echo $indice['nombre_img']?>" alt="Clase al aire libre" /> -->
            <h4><?php echo $indice['contenido']?></h4>
        </div>
     <?php   endforeach;?>
    </div>
  </section>

  <section id="contacto" class="section light">
    <h2>Contacto</h2>
    <p>📍 Zona norte, Buenos Aires<br />
       📩 <a href="mailto:colegioloshorneros@gmail.com">colegioloshorneros@gmail.com</a><br />
       📱 <a href="https://www.instagram.com/colegioloshorneros" target="_blank">@colegioloshorneros</a>
    </p>
  </section>

  <footer>
    <p>&copy; 2025 Colegio Los Horneros</p>
  </footer>

  <script src="script.js"></script>
</body>
</html>
