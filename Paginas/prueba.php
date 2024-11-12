<?php
echo "<form method='POST' action='' id='form-asignar'>";
                echo "<input type='hidden' name='mesa' value='2'>";
                echo "<label for='assigned_to'>Asignar a: </label>";
                echo "<input type='text' id='assigned_to' name='assigned_to' class='form-control mb-2'>";
                echo "<button type='submit' id='btn-asignar' class='btn btn-verde'>Asignar</button>";
                echo "</form>";


                if (isset($_POST['assigned_to'])) {
                    $assigned_to = $_POST['assigned_to'];
                    // $stmt_insert = $conn->prepare("INSERT INTO tbl_historial (fecha_A, assigned_by, assigned_to, id_mesa) VALUES (NOW(), ?, ?, ?)");
                    // $stmt_insert->bind_param("isi", $id_user, $assigned_to, $id_mesa);
                    // $stmt_insert->execute();
    
                    // if ($stmt_insert->affected_rows > 0) {
                        echo $assigned_to;
                        // echo "<p class='text-success'>Mesa $id_mesa asignada exitosamente a $assigned_to.</p>";
                    // } else {
                    //     echo "<p class='text-danger'>Error al asignar la mesa. Intenta de nuevo.</p>";
                    // }
                    // exit();
                    // $stmt_insert->close();
                }
    