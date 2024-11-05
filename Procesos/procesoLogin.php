<?php


if (!filter_has_var(INPUT_POST, 'enviar')) {
    header("Location: ../Paginas/login.php?error=inicioMal");
    exit();
}
$usr = mysqli_escape_string($conn, htmlspecialchars($_POST["username"]));
$pwd = mysqli_escape_string($conn, htmlspecialchars(SHA2($_POST["pwd"], 256)));

try {
    $sqlInicio = "SELECT tbl_camareros.id_camarero, tbl_camareros.pwd_camarero FROM tbl_camareros WHERE tbl_camareros.username_camarero = ?";

    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sqlInicio);
    mysqli_stmt_bind_param($stmt, "s", $usr);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) > 0) {

        $row = mysqli_fetch_assoc($resultado);
        $_SESSION["camareroID"] = $row["id_camarero"]

        if (!password_verify($pwd, $row["pwd_camarero"])) {
            header("Location: ../Paginas/login.php?error=datosMal");
            exit();
        }
    } else {
        header("Location: ../Paginas/login.php?error=datosMal");
        exit();
    }
    
    header("Location: ../Paginas/login.php");
    exit();

} catch (Exception $e) {
    echo "Error al iniciar sesión: " . $e->getMessage();
    die();
}





?>