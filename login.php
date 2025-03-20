<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['email'];
    $contrasena = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($contrasena, $user['contrasena'])) {
            session_start();
            $_SESSION['usuario_id'] = $user['usuario_id'];
            $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
            $_SESSION['correo'] = $user['correo'];

            header("Location: index.html");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
            header("Location: login.php?error=" . urlencode($error));
            exit();
        }
    } else {
        $error = "El correo no está registrado.";
        header("Location: login.php?error=" . urlencode($error));
        exit();
    }
}
$conn->close();
?>