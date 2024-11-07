<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesas de la Sala</title>
    <style>
        .btn-verde {
            background-color: green;
            color: white;
            border: none;
            padding: 10px 15px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn-rojo {
            background-color: red;
            color: white;
            border: none;
            padding: 10px 15px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<a href="salas.php"><button class="back">Volver a salas</button></a>

<?php
require_once "../Procesos/conection.php";
session_start();

// Comprobación de sesión activa
if (!isset($_SESSION["camareroID"])) {
    header('Location: ../index.php');
    exit();
} else {
    $id_user = $_SESSION["camareroID"];
    // sesion de sala
        if (isset($_POST['sala'])){
        $_SESSION['sala'] = $_POST['sala'];
        }
}


// Verificar si se ha enviado el nombre de la sala
if (isset($_SESSION['sala'])) {
    $nombre_sala = $_SESSION['sala']; 

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

        // Consultar las mesas en esa sala
        $stmt_mesas = $conn->prepare("
        SELECT m.id_mesa, m.n_asientos, 
        CASE 
            WHEN h.fecha_NA IS NULL AND h.id_mesa IS NOT NULL THEN 'Asignada'
            ELSE 'No Asignada'
        END AS estado_mesa
        FROM tbl_mesas m
        LEFT JOIN tbl_historial h ON m.id_mesa = h.id_mesa AND h.fecha_NA IS NULL
        WHERE m.id_sala = ?
        GROUP BY m.id_mesa
        "); 
        $stmt_mesas->bind_param("i", $id_sala);
        $stmt_mesas->execute();
        $resultado_mesas = $stmt_mesas->get_result();

        // Mostrar mesas como botones
        echo "<h2>Mesas en: $nombre_sala</h2>";
        echo "<form action='./asignar_mesa.php' method='POST'>";

        if ($resultado_mesas->num_rows > 0) {
            while ($mesa = $resultado_mesas->fetch_assoc()) {
                $id_mesa = htmlspecialchars($mesa['id_mesa']);
                $n_asientos = htmlspecialchars($mesa['n_asientos']);
                $estado_mesa = htmlspecialchars($mesa['estado_mesa']);
                
                // Clase del botón según el estado de la mesa
                $boton_clase = ($estado_mesa === 'Asignada') ? 'btn-rojo' : 'btn-verde';
                
                // Botón para cada mesa
                echo "<button type='submit' name='mesa' value='$id_mesa' class='$boton_clase'>Mesa $id_mesa (Asientos: $n_asientos)</button>";
            }
        } else {
            echo "<p>No hay mesas disponibles en esta sala.</p>";
        }

        echo "</form>"; // Cerrar formulario

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
