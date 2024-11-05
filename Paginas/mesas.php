<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesas de la Sala</title>
</head>
<body>

<a href="./salas.php"><button class="back">Volver</button></a>

<?php
// Conexión a la base de datos
require_once "../Procesos/conection.php";

// Verificar si se ha enviado el nombre de la sala
if (isset($_POST['sala'])) {
    $nombre_sala = $_POST['sala'];

    // Sanitizar el nombre de la sala
    $nombre_sala = htmlspecialchars($nombre_sala);

    // Consultar ID de la sala basada en el nombre
    $stmt = $conn->prepare("SELECT id_salas FROM tbl_salas WHERE name_sala = ?");
    $stmt->bind_param("s", $nombre_sala); 
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Obtener el ID de la sala
    if ($fila = $resultado->fetch_assoc()) {
        $id_sala = $fila['id_salas'];

        // Ahora consultar las mesas en esa sala
        $stmt_mesas = $conn->prepare("SELECT id_mesa, n_asientos, estado_sala FROM tbl_mesas WHERE id_sala = ?");
        $stmt_mesas->bind_param("i", $id_sala); 
        $stmt_mesas->execute();
        $resultado_mesas = $stmt_mesas->get_result();

        // Mostrar mesas
        echo "<h2>Mesas en la Sala: $nombre_sala</h2>";
        if ($resultado_mesas->num_rows > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>ID Mesa</th>
                        <th>Número de Asientos</th>
                        <th>Estado</th>
                    </tr>";
            while ($mesa = $resultado_mesas->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($mesa['id_mesa']) . "</td>
                        <td>" . htmlspecialchars($mesa['n_asientos']) . "</td>
                        <td>" . htmlspecialchars($mesa['estado_sala']) . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay mesas disponibles en esta sala.</p>";
        }

        // Cerrar declaración de mesas
        $stmt_mesas->close();
    } else {
        echo "<p>No se encontró la sala especificada.</p>";
    }

    // Cerrar declaración de sala
    $stmt->close();
} else {
    echo "<p>No se ha seleccionado ninguna sala.</p>";
}

// Cerrar conexión
$conn->close();
?>

</body>
</html>
