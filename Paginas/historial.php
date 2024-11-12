<?php
require_once "../Procesos/conection.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION["camareroID"])) {
    header('Location: ../index.php?error=nosesion');
    exit();
}

// Inicializar variables
$SalaSeleccionada = isset($_POST['room']) ? $_POST['room'] : null;
$selectedTable = isset($_POST['table']) ? $_POST['table'] : null;
$filterDate = isset($_POST['filter_date']) ? $_POST['filter_date'] : null;
$filterCamarero = isset($_POST['filter_camarero']) ? $_POST['filter_camarero'] : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Historial de Mesas</title>
    <link rel="stylesheet" href="../CSS/estilos-historial.css">
</head>
<body>
<a href="salas.php"><button class="back">Volver a salas</button></a>

<h2>Selecciona una Sala y Mesa para ver el Historial</h2>

<!-- Room Selection Form -->
<form method="post" action="historial.php">
    <label for="room">Seleccione una Sala:</label>
    <select name="room" id="room" onchange="this.form.submit()">
        <option value="" disabled>Seleccione una Sala</option>
        <?php
        // Query to fetch rooms
        $stmt_rooms = $conn->prepare("SELECT id_salas, name_sala FROM tbl_salas");
        $stmt_rooms->execute();
        $result_rooms = $stmt_rooms->get_result();

        while ($row = $result_rooms->fetch_assoc()) {
            $selected = ($SalaSeleccionada == $row['id_salas']) ? 'selected' : '';
            echo "<option value='" . $row['id_salas'] . "' $selected>" . $row['name_sala'] . "</option>";
        }

        $stmt_rooms->close();
        ?>
    </select>
</form>

<?php if ($SalaSeleccionada): ?>
    <!-- Table Selection Form (only shown if a room is selected) -->
    <form method="post" action="historial.php">
        <label for="table" >Seleccione una Mesa:</label>
        <select name="table" id="table" onchange="this.form.submit()">
            <option value="" disabled>Seleccione una Mesa</option>
            <?php
            // Query to fetch tables for the selected room, with their assignment status
            $stmt_tables = $conn->prepare("
                SELECT m.id_mesa, m.n_asientos,
                    CASE
                        WHEN h.fecha_NA IS NULL AND h.id_mesa IS NOT NULL THEN 'Asignada'
                        ELSE 'No Asignada'
                    END AS estado_mesa
                FROM tbl_mesas m
                LEFT JOIN tbl_historial h ON m.id_mesa = h.id_mesa AND h.fecha_NA IS NULL
                WHERE m.id_sala = ?
            ");
            $stmt_tables->bind_param("i", $SalaSeleccionada);
            $stmt_tables->execute();
            $result_tables = $stmt_tables->get_result();

            while ($row = $result_tables->fetch_assoc()) {
                $selected = ($selectedTable == $row['id_mesa']) ? 'selected' : '';
                echo "<option value='" . $row['id_mesa'] . "' $selected>Mesa " . $row['id_mesa'] . " (" . $row['n_asientos'] . " asientos, " . $row['estado_mesa'] . ")</option>";
            }

            $stmt_tables->close();
            ?>
        </select>
        <input type="hidden" name="room" value="<?php echo $SalaSeleccionada; ?>">
    </form>
<?php endif; ?>

<?php if ($selectedTable): ?>
    <!-- Filters for the table history -->
    <form method="post" action="historial.php">
        <input type="hidden" name="room" value="<?php echo $SalaSeleccionada; ?>">
        <input type="hidden" name="table" value="<?php echo $selectedTable; ?>">

        <label for="filter_date">Filtrar por Fecha:</label>
        <input type="date" name="filter_date" id="filter_date" value="<?php echo $filterDate; ?>" onchange="this.form.submit()">

        <label for="filter_camarero">Filtrar por Camarero:</label>
        <select name="filter_camarero" id="filter_camarero" onchange="this.form.submit()">
            <option value="" disabled>Seleccione un Camarero</option>
            <?php
            // Query to fetch all camareros
            $stmt_camareros = $conn->prepare("SELECT id_camarero, name_camarero, surname_camarero FROM tbl_camarero");
            $stmt_camareros->execute();
            $result_camareros = $stmt_camareros->get_result();

            while ($row = $result_camareros->fetch_assoc()) {
                $selectedCamarero = ($filterCamarero == $row['id_camarero']) ? 'selected' : '';
                echo "<option value='" . $row['id_camarero'] . "' $selectedCamarero>" . $row['name_camarero'] . " " . $row['surname_camarero'] . "</option>";
            }

            $stmt_camareros->close();
            ?>
        </select>
    </form>

    <h3>Historial de Mesa <?php echo $selectedTable; ?></h3>
    <?php
    // Consulta SQL base
    $sql = "
        SELECT h.fecha_A, h.fecha_NA, c.name_camarero, c.surname_camarero, h.assigned_to
        FROM tbl_historial h
        JOIN tbl_camarero c ON h.assigned_by = c.id_camarero
        WHERE h.id_mesa = ?
    ";

    // Variables para el almacenamiento de parámetros
    $parametros = [$selectedTable];
    $tipos = "i";

    // Aplicar filtros si están configurados
    if ($filterDate) {
        $sql .= " AND DATE(h.fecha_A) = ?";
        $parametros[] = $filterDate;
        $tipos .= "s"; // Tipo de dato string para la fecha
    }

    if ($filterCamarero) {
        $sql .= " AND h.assigned_by = ?";
        $parametros[] = $filterCamarero;
        $tipos .= "i"; // Tipo de dato entero para el id del camarero
    }

    $sql .= " ORDER BY h.fecha_A DESC";

    // Preparar y ejecutar la consulta
    $stmt_history = $conn->prepare($sql);

    // Vincular dinámicamente los parámetros
    $stmt_history->bind_param($tipos, ...$parametros);
    $stmt_history->execute();
    $result_history = $stmt_history->get_result();

    if ($result_history->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Fecha Asignación</th>
                    <th>Fecha No Asignación</th>
                    <th>Asignado Por</th>
                    <th>Asignado A</th>
                </tr>";
        while ($row = $result_history->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['fecha_A'] . "</td>
                    <td>" . ($row['fecha_NA'] ? $row['fecha_NA'] : "N/A") . "</td>
                    <td>" . $row['name_camarero'] . " " . $row['surname_camarero'] . "</td>
                    <td>" . $row['assigned_to'] . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay historial para esta mesa.</p>";
    }

    $stmt_history->close();
    ?>
<?php endif; ?>

</body>
</html>

<?php
$conn->close();
?>
