<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Mesas</title>
    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- css -->
    <link rel="stylesheet" href="../CSS/estilos-asignar.css">
</head>
<body>
    <div class="container text-center">
        <?php
        require_once "../Procesos/conection.php";
        session_start();

        // Comprobación de sesión activa
        if (!isset($_SESSION["camareroID"]) && !isset($_SESSION['sala'])) {
            header('Location: ../index.php');
            exit();
        }

        $id_user = $_SESSION["camareroID"]; // ID del camarero actual


        if (isset($_POST['mesa'])) {
            $id_mesa = $_POST['mesa'];

            if (isset($_POST['asignar'])) {
                $assigned_to = trim($_POST['assigned_to']);  // Limpiar espacios en blanco 

                // No vacío
                if (empty($assigned_to)) {
                    echo "<p class='text-danger'>No se puede asignar a un fantasma</p>";
                } else {
                    // Escapar los datos para evitar vulnerabilidades de seguridad
                    $assigned_to = htmlspecialchars($assigned_to, ENT_QUOTES, 'UTF-8');

                    // Realizar la inserción en la base de datos
                    $stmt_insert = $conn->prepare("INSERT INTO tbl_historial (fecha_A, assigned_by, assigned_to, id_mesa) VALUES (NOW(), ?, ?, ?)");
                    $stmt_insert->bind_param("isi", $id_user, $assigned_to, $id_mesa);
                    $stmt_insert->execute();

                    if ($stmt_insert->affected_rows > 0) {
                        echo "<p class='text-success'>Mesa $id_mesa asignada exitosamente a $assigned_to.</p>";
                    } else {
                        echo "<p class='text-danger'>Error al asignar la mesa. Intenta de nuevo.</p>";
                    }

                    $stmt_insert->close();
                }
            }

            // Consulta para verificar si la mesa está asignada actualmente
            $stmt = $conn->prepare("
                SELECT h.id_historial, h.fecha_A, h.assigned_by, h.assigned_to, c.name_camarero, c.surname_camarero
                FROM tbl_historial h
                JOIN tbl_camarero c ON h.assigned_by = c.id_camarero
                WHERE h.id_mesa = ? AND h.fecha_NA IS NULL
                ORDER BY h.fecha_A DESC
                LIMIT 1
            ");
            $stmt->bind_param("i", $id_mesa);
            $stmt->execute();
            $resultado = $stmt->get_result();

            // Mostrar información de la asignación si existe
            if ($resultado->num_rows > 0) {
                $asignacion = $resultado->fetch_assoc();
                echo "<a href='mesas.php'><button class='btn btn-secondary back'>Volver a mesas</button></a>";
                echo "<h2>Detalles de Asignación de la Mesa $id_mesa</h2>";
                echo "<p><strong>Fecha de Asignación:</strong> " . htmlspecialchars($asignacion['fecha_A']) . "</p>";
                echo "<p><strong>Asignada por:</strong> " . htmlspecialchars($asignacion['name_camarero']) . " " . htmlspecialchars($asignacion['surname_camarero']) . "</p>";
                echo "<p><strong>Asignada a:</strong> " . htmlspecialchars($asignacion['assigned_to']) . "</p>";

                // Botón de desasignar
                echo "<form method='POST' action=''>";
                echo "<input type='hidden' name='mesa' value='$id_mesa'>";
                echo "<button type='submit' name='desasignar' class='btn btn-rojo'>Desasignar</button>";
                echo "</form>";
            } else {
                // Mensaje si la mesa no está asignada
                echo "<a href='mesas.php'><button class='btn btn-secondary back'>Volver a mesas</button></a>";
                echo "<p>Esta mesa no está asignada actualmente.</p>";

                // Botón de asignar
                echo "<form method='POST' action=''>";
                echo "<input type='hidden' name='mesa' value='$id_mesa'>";
                echo "<label for='assigned_to'>Asignar a: </label>";
                echo "<input type='text' id='assigned_to' name='assigned_to' class='form-control mb-2'>";
                echo "<button type='submit' name='asignar' class='btn btn-verde'>Asignar</button>";
                echo "</form>";
            }

            // Liberar recursos
            $stmt->close();
        } else {
            echo "<p>No se ha seleccionado ninguna mesa.</p>";
        }

        // Cerrar conexión
        $conn->close();
        ?>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>