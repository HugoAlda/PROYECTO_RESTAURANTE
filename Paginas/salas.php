<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/estilos-salas.css">
    <title>TPV Salas</title>
</head>
<body>
    <form action="./mesas.php" method="POST">
        <div class="container">
            <?php
            require_once "../Procesos/conection.php";
            session_start();

            // Sesion
            if (!isset($_SESSION["camareroID"])) {
                header('Location: ../index.php?error=nosesion');
                exit();
            } else {
                $id_user = $_SESSION["camareroID"];
            }

            // Consulta SQL para obtener las salas usando una sentencia preparada
            $consulta = "SELECT name_sala FROM tbl_salas";
            $stmt = $conn->prepare($consulta);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Obtener los resultados
                $resultado = $stmt->get_result();

                // Generación de botones para cada sala
                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_assoc()) {
                        $nombre_sala = htmlspecialchars($fila['name_sala']); // Sanitizar el nombre de la sala
                        echo "<input type='submit' name='sala' value='$nombre_sala' class='input_sala input_$nombre_sala'>";
                    }
                } else {
                    echo "<p>No hay salas disponibles</p>";
                }
            } else {
                echo "<p>Error al ejecutar la consulta</p>";
            }

            // Cerrar la declaración y la conexión
            $stmt->close();
            ?>
        </div>
    </form>
    <div class="contenedor">
        <div class="footer">
            <a href="../index.php"><button type="submit" class="logout">Cerrar Sesión</button></a>
            <h1>¡Selecciona una sala para ver su disponibilidad de mesas!</h1>
        </div>
        <div class="contenedor-superior">
            <div class="mapeado">
                <!-- La imagen está como fondo -->
            </div>
        </div>
    </div>
</body>
</html>