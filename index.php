<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/estilos.css">
    <script src="../JS/validaciones.js"></script>
    <title>Bienvenido</title>
</head>
<body>
    <form id="login" class="login" method="POST" action="proceso_login.php">
        <label>Nombre de usuario:</label>
        <input type="text" id="username" name="username" placeholder="Nombre de usuario">
        <br>
        <label>Contraseña:</label>
        <input type="password" id="pwd" name="pwd" placeholder="Contraseña">
        <br>
        <input type="submit" value="Enviar" id="enviar">
    </form>
</body>
</html>