<?php
// -----------------------------------------------------------
// 0. CONFIGURACIÓN GLOBAL (Conexión a la base de datos)
// -----------------------------------------------------------
// Asegúrate de que este archivo apunta a tu conexión a la DB ($pdo)
require_once __DIR__ . '/../config/config.php';

// -----------------------------------------------------------
// 1. LÓGICA DE PROCESAMIENTO DEL FORMULARIO AJAX
// -----------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    header('Content-Type: application/json');

    try {
        // 1. Recoger y sanitizar los datos
        $nombre = filter_input(INPUT_POST, 'form_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'form_email', FILTER_SANITIZE_EMAIL);
        $telefono = filter_input(INPUT_POST, 'form_phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // Los datos de 'tema' y 'fecha_nac' se recogen del formulario,
        // pero NO SE USAN en la DB para evitar el error.
        //$tema = filter_input(INPUT_POST, 'form_subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //$fecha_nac = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $mensaje = filter_input(INPUT_POST, 'form_message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // 2. Validación básica
        if (empty($nombre) || empty($email) || empty($mensaje)) {
            echo json_encode(['status' => 'error', 'message' => 'Faltan campos obligatorios.']);
            exit;
        }

        // 3. Preparar e insertar en la base de datos
        // ✅ CORRECCIÓN: La consulta SQL ahora SÓLO incluye las 4 columnas existentes:
        // (nombre, correo, telefono, mensaje)
        $sql = "INSERT INTO contactos (nombre, correo, telefono, mensaje) 
                VALUES (:nombre, :correo, :telefono, :mensaje)";
        $stmt_insert = $pdo->prepare($sql);
        
        // ✅ CORRECCIÓN: Los parámetros de execute también se ajustan a las 4 columnas:
        $stmt_insert->execute([
            ':nombre' => $nombre,
            ':correo' => $email,
            ':telefono' => $telefono,
            ':mensaje' => $mensaje,
        ]);

        // 4. Enviar respuesta de éxito (JSON)
        echo json_encode(['status' => 'success', 'message' => 'Datos guardados correctamente.']);
        exit;

    } catch (PDOException $e) {
        // Dejo el mensaje de error detallado por si hubiera algún otro problema (e.g., campo NOT NULL)
        error_log("Error de DB en index.php: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error de DB: ' . $e->getMessage()]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error interno del servidor.']);
        exit;
    }
}

// Fin del bloque de procesamiento AJAX.

// -----------------------------------------------------------
// 2. LÓGICA DE CARGA DE DATOS PARA LA PÁGINA (ORIGINAL)
// -----------------------------------------------------------

// Lógica de carga de datos para el frontend (HTML)
$stmt = $pdo->query("SELECT * FROM secciones WHERE pagina = 'inicio'");
$inicio = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM secciones WHERE pagina = 'propuesta' and subcategoria= 'inicial'");
$nivelinicial = $stmt->fetchAll();
$stmt = $pdo->query("SELECT * FROM secciones WHERE pagina = 'propuesta' and subcategoria= 'primario'");
$nivelprimario = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM secciones WHERE pagina = 'caracteristicas' ");
$caracteristicas = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM secciones WHERE pagina = 'galeria'");
$galeria = $stmt->fetchAll();
// -----------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colegio Los Horneros</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="stylefeatures.css">
</head>
<body>

    <header class="main-header">
        <div class="logo">Colegio Los Horneros</div>
        <nav class="main-nav">
            <ul>
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#propuesta">Propuesta</a></li>
                <li><a href="#profesores">Profesores</a></li>
                <li><a href="#contactForm">Contacto</a></li>
            </ul>
        </nav>
        <button class="btn-cita">Pide una Cita</button>
    </header>

    <section class="slider">
    <div class="slides">
        <div class="slide active" style="background-image:url('https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');"></div>
        <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1532&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');"></div>
        <div class="slide" style="background-image:url('https://plus.unsplash.com/premium-photo-1663126319781-f4de55c7ebd4?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');"></div>
    </div>
    
    <div class="slider-text">
        <?php foreach ($inicio as $indice): ?>
            <h1><?php echo htmlspecialchars($indice['titulo']); ?></h1>
            <p><?php echo htmlspecialchars($indice['contenido']); ?></p>
        <?php endforeach; ?>
    </div>
</section>
        
    <section class="features-section" id="propuesta">
    <h2>Nuestra Propuesta</h2>
    <div class="feature-grid">
        
        <div class="feature-card color-green">
            <?php foreach ($nivelinicial as $indice): ?>
                <h3><?php echo htmlspecialchars($indice['titulo']); ?></h3>
                <p><?php echo htmlspecialchars($indice['contenido']); ?></p>
                
                <p class="more-content hidden" id="more-info-<?php echo $indice['id']; ?>">
                    ¡Contenido extra para Nivel Inicial! Más detalles del título "<?php echo htmlspecialchars($indice['titulo']); ?>".
                </p>

                <button 
                    class="btn btn-xs btn-theme-colored4 btn-outline-light toggle-button" 
                    data-target="more-info-<?php echo $indice['id']; ?>"
                    style="width: 100px;background-color: purple;"
                >
                    + Info
                </button>
            <?php endforeach; ?>
        </div>

        <div class="feature-card color-green">
            <?php foreach ($nivelprimario as $indice): ?> <h3><?php echo htmlspecialchars($indice['titulo']); ?></h3>
                <p><?php echo htmlspecialchars($indice['contenido']); ?></p>
                
                <p class="more-content hidden" id="more-info-primario-<?php echo $indice['id']; ?>">
                    ¡Contenido extra para Nivel Primario! Más detalles del título "<?php echo htmlspecialchars($indice['titulo']); ?>".
                </p>

                <button 
                    class="btn btn-xs btn-theme-colored4 btn-outline-light toggle-button" 
                    data-target="more-info-primario-<?php echo $indice['id']; ?>"
                    style="width: 100px;background-color: purple;"
                >
                    + Info
                </button>
            <?php endforeach; ?>
        </div>
        
    </div>
</section>
    <section class="section-contact">
        <div class="row mt-40" style="display: flex;padding: 10px">
            <div class="col-md-6 col-xl-4 " style="width: 80%;" >
              <div class="about-box-contents" >
                <div class="destails">
                  <h3 class="text-theme-colored2">Colegio Los Horneros</h3>
                  <p data-tm-font-weight="600">
                    Cuenta con una trayectoria de 30 años de experiencia educando con <strong>compromiso y dedicacion</strong><br>
                    Estas buscando nuevas alternativas para tus hijos, te damos 3 razones de porque elegirnos
                    </p>
                </div>
                <ul class="list-unstyled mb-10">
                  <li><i class="far fa-hand-point-right text-theme-colored1"></i>Educación Personalizada</li>
                  <li><i class="far fa-hand-point-right text-theme-colored2"></i>Inglés Intensivo</li>
                  <li><i class="far fa-hand-point-right text-theme-colored3"></i>Talleres Pedagógico</li>
                  
                </ul>
              
              </div>
              <a class="btn btn-xs btn-theme-colored2 btn-outline-light mb-30" href="#">Ver más</a>
            </div>
            <div class="col-md-6 col-xl-4 " style="width: 30%;">
              <div class="p-30 bg-theme-colored2 mb-30">
                                <form id="contactForm" name="contact_form" class="contact-form" action="" method="post" style="width:500px;">
                    <div class="row">
                      <div class="col-sm-12">
                        <h4 class="text-white">Información <span class="text-theme-colored4">Solicitada</span></h4>
                      </div>
                      <div class="col-sm-12">
                        <div class="mb-3">
                          <input name="form_name" class="form-control" type="text" placeholder="Tu nombre" style="width: 55%;">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb-3">
                          <input name="form_email" class="form-control required email" type="email" placeholder="Email Address" style="width: 55%;">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb-3">
                          <input name="form_phone" class="form-control" type="text" placeholder="Telefono" style="width: 55%;">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb-3">
                          <select name="form_subject" class="form-control required" style="width: 55%;">
                            <option>Elija Tema</option>
                            <option value="nivelinicial">Nivel Inicial</option>
                            <option value="nivelprimario">Nivel Primario</option>
                            
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb-3">
                          <input name="date" class="form-control date-picker" type="text" placeholder="Fecha de Nacimiento" style="width: 55%;">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="mb-3">
                          <textarea name="form_message" rows="3" class="form-control textarea required" placeholder="Escriba su consulta" style="width: 55%;"></textarea>
                        </div>
                        
                        <div id="responseMessage" style="margin-bottom: 10px; font-weight: bold; color: white;"></div>
                        
                        <div class="mb-3 mb-10 d-grid">
                          <input name="form_botcheck" class="form-control" type="hidden" value="">
                          <button type="submit" class="btn btn-xs btn-theme-colored4 btn-outline-light" data-loading-text="Please wait..." style="width: 55%;"> Enviar Mensaje</button>
                        </div>
                      </div>
                    </div>
                </form>
              </div>
            </div>
        </div>
    </section>
           <div class="container pb-90">
        <div class="section-title">
          <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
              <div class="tm-sc-section-title section-title text-center">
                <div class="title-wrapper" style="text-align: center;">
                  <h2 class="title">Nuestras <span class="text-theme-colored3" style="font-size: 46px;">Características</span></h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="section-content" >
          <div class="row" style="display: flex;" >
               <?php
                    $i =0;
                    $colores = ["#e64a43", "#ffae1b", "#21b3c9"];  
                        foreach ($caracteristicas as $sec) {
                            $color = $colores[$i % count($colores)];    ?>
            <div class="col-sm-6 col-xl-4" style="width: 30%;margin: 0 20px;">
                    
              <div class="tm-sc-icon-box icon-box icon-left text-left iconbox-centered-in-responsive  animate-icon-on-hover animate-icon-rotate-y p-30 mb-30" data-tm-border-radius="15px" style="border-radius: 15px;background:<?= $color ?>">
                <div class="icon-box-wrapper">
                  <div class="icon-wrapper"> <a class="icon text-white"> <i class="fas fa-graduation-cap"></i> </a></div>
                  <div class="icon-text">
                  
                    <h5 class="icon-box-title text-white"><?= htmlspecialchars($sec['titulo']) ?></h5>
                    <div class="content">
                      <p class="text-white"><?= htmlspecialchars($sec['contenido']) ?></p>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  
                </div>
              </div>
            </div>
                <?php
                            $i++;
                            }
                        ?>
          </div>
        </div>
      </div>

    <footer class="main-footer">
        <p>&copy; 2024 Colegio Los Horneros | Derechos Reservados</p>
        <div class="social-links">
            <a href="#">Facebook</a> | <a href="#">Instragram</a>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lógica original del slider
        const slides = document.querySelectorAll('.slide');
        if (slides.length > 0) { 
            let currentSlide = 0;
            const slideInterval = 3000; 

            function nextSlide() {
                slides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide + 1) % slides.length;
                slides[currentSlide].classList.add('active');
            }
            slides.forEach((slide, index) => {
                slide.classList.remove('active');
                if (index === 0) {
                    slide.classList.add('active');
                }
            });
            setInterval(nextSlide, slideInterval);
        }
        
        // Lógica original del botón de +Info
        const buttons = document.querySelectorAll('.toggle-button');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetContent = document.getElementById(targetId);

                if (targetContent) {
                    targetContent.classList.toggle('hidden');
                    
                    if (targetContent.classList.contains('hidden')) {
                        this.textContent = '+ Info';
                    } else {
                        this.textContent = '- Info';
                    }
                } else {
                    console.error('No se encontró el contenido con ID:', targetId); 
                }
            });
        });
        
        // ------------------------------------------------------------------
        // LÓGICA DE ENVÍO AJAX HACIA EL MISMO ARCHIVO (index.php)
        // ------------------------------------------------------------------
        
        const contactForm = document.getElementById('contactForm');
        
        if (contactForm) {
            contactForm.addEventListener('submit', function(event) {
                // Previene el envío estándar del formulario (evita la recarga)
                event.preventDefault(); 

                const form = event.target;
                const formData = new FormData(form); 
                const responseDiv = document.getElementById('responseMessage');
                
                // Mensaje de carga
                responseDiv.textContent = 'Enviando datos...';
                responseDiv.style.color = 'yellow';

                // LA URL AHORA ES EL MISMO ARCHIVO: index.php o simplemente '/'
                fetch(window.location.href, { 
                    method: 'POST',
                    body: formData 
                })
                .then(response => {
                    if (!response.ok) {
                        // Trata errores HTTP/red
                        throw new Error('Error de servidor HTTP: ' + response.status);
                    }
                    return response.json(); // Esperamos JSON
                })
                .then(data => {
                    // Manejar la respuesta JSON del PHP (success/error)
                    if (data.status === 'success') {
                        responseDiv.textContent = '✅ ¡Mensaje enviado y datos guardados!';
                        responseDiv.style.color = 'lightgreen';
                        form.reset(); // Limpiar formulario
                    } else {
                        // Esto mostrará el error exacto de la DB (si hay otros problemas)
                        responseDiv.textContent = '❌ Error al guardar: ' + (data.message || 'Error desconocido.');
                        responseDiv.style.color = 'red';
                    }
                })
                .catch(error => {
                    // Manejar errores de conexión o parsing
                    console.error('Error:', error);
                    responseDiv.textContent = '❌ Error de conexión o formato. Intente más tarde.';
                    responseDiv.style.color = 'red';
                });
            });
        }
    });
    </script>
</body>
</html>