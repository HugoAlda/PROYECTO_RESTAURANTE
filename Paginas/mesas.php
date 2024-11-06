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

<a href="./index.php"><button class="back">Volver</button></a>

<?php
require_once "../Procesos/conection.php";
session_start();

// Sesion
if (!isset($_SESSION["camareroID"])) {
    header('Location: ../index.php');
    exit();
} else {
    $id_user = $_SESSION["camareroID"];
}

// Verificar si se ha enviado el nombre de la sala
if (isset($_POST['sala'])) {
    $nombre_sala = $_POST['sala'];

    // Sanitizar el nombre de la sala
    $nombre_sala = htmlspecialchars($nombre_sala);

    // Consultar ID de la sala basada en el nombre
    $stmt = $conn->prepare("SELECT id_salas FROM tbl_salas WHERE name_sala = ?");
    $stmt->bind_param("s", $nombre_sala); // Vincular parámetro
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Obtener el ID de la sala
    if ($fila = $resultado->fetch_assoc()) {
        $id_sala = $fila['id_salas'];

        // Ahora consultar las mesas en esa sala
        $stmt_mesas = $conn->prepare("SELECT id_mesa, n_asientos, estado_sala FROM tbl_mesas WHERE id_sala = ?");
        $stmt_mesas->bind_param("i", $id_sala); // Vincular el ID de sala
        $stmt_mesas->execute();
        $resultado_mesas = $stmt_mesas->get_result();

        // Mostrar mesas como botones de tipo submit
        echo "<h2>Mesas en: $nombre_sala</h2>";
        echo "<form action='./asignar_mesa.php' method='POST'>"; 

        if ($resultado_mesas->num_rows > 0) {
            while ($mesa = $resultado_mesas->fetch_assoc()) {
                $id_mesa = htmlspecialchars($mesa['id_mesa']);
                $n_asientos = htmlspecialchars($mesa['n_asientos']);
                $estado_sala = htmlspecialchars($mesa['estado_sala']);
                
                // Determinar la clase del botón según el estado
                $boton_clase = ($estado_sala == 'NA') ? 'btn-verde' : 'btn-rojo';
                
                // Botón para cada mesa
                echo "<input type='submit' name='mesa' value='Mesa $id_mesa (Asientos: $n_asientos)' class='$boton_clase'>";
            }
        } else {
            echo "<p>No hay mesas disponibles en esta sala.</p>";
        }

        echo "</form>"; // Cerrar el formulario

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
