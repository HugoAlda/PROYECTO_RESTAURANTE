<?php
require_once "../Procesos/conection.php";

// Verificar si se ha enviado el ID de la mesa
if (isset($_POST['mesa'])) {
    // Sanitizar el valor recibido
    $mesa_seleccionada = htmlspecialchars($_POST['mesa']);
    
    // Extraer el ID de la mesa del valor del botón
    preg_match('/Mesa (\d+)/', $mesa_seleccionada, $matches);
    
    if (isset($matches[1])) {
        $id_mesa = intval($matches[1]); // ID de la mesa

        // Mostrar el formulario para ingresar el nombre del cliente
        echo "<h2>Asignar mesa $id_mesa</h2>";
        echo "<form action='./procesar_mesa.php' method='POST'>";
        echo "<label for='nombre_cliente'>Nombre del Cliente:</label>";
        echo "<input type='text' id='nombre_cliente' name='nombre_cliente' required>";
        echo "<input type='hidden' name='mesa' value='$mesa_seleccionada'>"; // Pasar el valor de la mesa
        echo "<input type='submit' value='Asignar Mesa'>";
        echo "</form>";
    } else {
        echo "<p>No se pudo obtener el ID de la mesa.</p>";
    }
} elseif (isset($_POST['nombre_cliente'])) {
    // Si ya se ha enviado el nombre del cliente, procesar la asignación de la mesa
    $nombre_cliente = htmlspecialchars($_POST['nombre_cliente']);
    $mesa_seleccionada = $_POST['mesa']; // Obtener el valor de la mesa
    preg_match('/Mesa (\d+)/', $mesa_seleccionada, $matches);
    
    if (isset($matches[1])) {
        $id_mesa = intval($matches[1]); // ID de la mesa

        // Datos adicionales a insertar
        $assigned_by = 1; // Este debe ser el ID del camarero actual (cámbialo según tu lógica de autenticación)

        // Preparar la consulta para actualizar la mesa
        $stmt = $conn->prepare("UPDATE tbl_mesas SET assigned_by = ?, assigned_to = ?, estado_sala = 'A' WHERE id_mesa = ?");
        $stmt->bind_param("isi", $assigned_by, $nombre_cliente, $id_mesa); // Vincular parámetros

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "<p>La mesa ha sido asignada exitosamente a $nombre_cliente.</p>";
        } else {
            echo "<p>Error al asignar la mesa: " . $stmt->error . "</p>";
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "<p>No se pudo obtener el ID de la mesa.</p>";
    }
} else {
    echo "<p>No se ha seleccionado ninguna mesa.</p>";
}

// Cerrar conexión
$conn->close();
?>
