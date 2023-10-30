<?php
require_once 'bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usu = comprobar_usuario($_POST['usuario'], $_POST['clave']);

    if ($usu === false) {
        $err = true;
        $usuario = $_POST['usuario'];
    } else {
        session_start();
        $_SESSION['usuario'] = $usu;
        header("Location:ejemplo.php");
        return;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

</head>

<?php
if (isset($err) && $err == true) {
    echo "<p>Revise usuario y contraseña.</p>";
}
?>

<body>
    <form action="" method="post">
        usuario: <input type="text" name="usuario" value="<?php if (isset($usuario)) {
                                                                echo $usuario;
                                                            } ?>">
        clave: <input type="password" name="clave">
        <input type="submit" name="Enviar">
    </form>
</body>

</html>