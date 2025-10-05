<?php
    require_once __DIR__ . '/../config/config.php';
?>
    <form  method="POST">
        nombre:<input type="text" name="nombre" value="<?php echo $mostrar['nombre']?>"><br>
        apellido1:<input type="text" name="ape1" value="<?php echo $mostrar['apellido1']?>"><br>
        apellido2<input type="text" name="ape2" value="<?php echo $mostrar['apellido2']?>"><br>
        ciudad:<input type="text" name="ciudad" value="<?php echo $mostrar['ciudad']?>"><br>
        categoria: <input type="text" name="cat" value="<?php echo $mostrar['categoria']?>"><br>

        <input type="submit" name="enviar" value="modificar"><br>
    </form>
<?php
    if($_SERVER["REQUEST_METHOD"]==='POST'){
        if(isset($_POST['enviar'])){
            $nom = $_POST['nombre'];
            $ape = $_POST['ape1'];
            $ape2 = $_POST['ape2'];
            $ciudad = $_POST['ciudad'];
            $cat = $_POST['cat'];

            $modificar = mysqli_query($con, "UPDATE cliente SET nombre = '$nom',apellido1 = '$ape',apellido2 = '$ape2',ciudad = '$ciudad',categoria = '$cat' WHERE id='$id'");

            header('location: clase2508.php');
        }
    }