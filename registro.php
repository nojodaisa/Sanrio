<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = trim($_POST['nombre_ususario'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    $contrasenaConfirm = trim($_POST['contrasena-confirm'] ?? '');

    if (empty($nombre_usuario) || empty($correo) || empty($contrasena) || empty($contrasenaConfirm)) {
        $resultado = "⚠️ Por favor, complete todos los campos";
        redirigir($resultado);
    }

    if (!preg_match('/^[a-zA-Z\s]+$/', $nombre_usuario)) {
        $resultado = "⚠️ Digite solo letras en el nombre";
        redirigir($resultado);
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $resultado = "⚠️ Digite un correo electrónico válido";
        redirigir($resultado);
    }

    if (strlen($contrasena) < 6) {
        $resultado = "⚠️ La contraseña debe tener al menos 6 caracteres";
        redirigir($resultado);
    }

    if ($contrasena !== $contrasenaConfirm) {
        $resultado = "⚠️ Las contraseñas no coinciden";
        redirigir($resultado);
    }

    $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO usuarios (nombre_usuario, correo, contrasena) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $conexion->error);
        }

        $stmt->bind_param("sss", $nombre, $correo, $hashed_password);

        if ($stmt->execute()) {
            $resultado = "✅ Registro exitoso";
        } else {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        $resultado = "❌ Ocurrió un error: " . $e->getMessage();
    }

    $conexion->close();
    redirigir($resultado);
} else {
    $resultado = "⚠️ Solicitud inválida";
    redirigir($resultado);
}

function redirigir($mensaje)
{
    header('Location: registro.php?var=' . urlencode($mensaje));
    exit();
}
?>