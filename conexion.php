<?php
    $conexion = mysqli_connect("127.0.0.1", "root", "", "sanriostar");

    // Consulta de prueba
$resultado = $conexion->query("SHOW TABLES");

if ($resultado) {
    echo "✅ Conexión exitosa. Tablas en la base de datos:";
    while ($fila = $resultado->fetch_array()) {
        echo "<br>" . $fila[0];
    }
} else {
    echo "❌ Error al ejecutar la consulta: " . $conexion->error;
}

$conexion->close();
?>
