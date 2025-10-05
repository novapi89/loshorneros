 <?php require_once __DIR__ . '/../config/config.php';

    $stmt = $pdo->query("SELECT * FROM secciones ");
    $secciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h3>PANEL ADMINISTRACION</h3>

    <table border="1"> 
        <tr>
            <td>Titulo</td>
            <td>Contenido</td>
            <td>Acciones</td>
        </tr>

        <?php foreach ($secciones as $indice): ?>
            <tr>
                <td><?php echo $indice['titulo']; ?></td>
                <td><?php echo $indice['contenido']; ?></td>
                <td><a href="editar.php?id=<?php echo $indice['id'] ?>">Editar</a></td>
            </tr>
        <?php endforeach ?>
    </table>
</body>
</html>