<?php
     require_once __DIR__ . '/../config/config.php';

    $id = intval($_GET['id']);

    //buscar en la base el valor de ese id
    $stmt = $pdo -> prepare("SELECT * FROM secciones WHERE id = ? ");
    $stmt -> execute([$id]);

    $secciones = $stmt -> fetch(PDO::FETCH_ASSOC);


    if($_SERVER["REQUEST_METHOD"]==='POST'){
        if(isset($_POST['enviar'])){
            $titulo = $_POST['titulo'];
            $contenido = $_POST['contenido'];
            $img = $_POST['img'];
        
            $modificar = $pdo -> prepare("UPDATE secciones SET titulo = ?, contenido = ? WHERE id=?");
            $modificar -> execute([$titulo, $contenido, $id]);

            header('location: index.php');
        }
    }
?>
        <form  method="POST">
            titulo:<input type="text" name="titulo" value="<?php echo $secciones['titulo']?>"><br>
            contenido: <textarea name="contenido" id=""><?php echo $secciones['contenido']?></textarea>
            <br>
            imagenes:<input type="file" name="img" ><br>

        <input type="submit" name="enviar" value="modificar"><br>
    </form>