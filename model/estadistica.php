<?php

function getMorososData() {
    // Configura tus credenciales y detalles de conexión
    $host = "localhost";
    $dbname = "condominio_jmv02";
    $user = "root";
    $password = "";

    try {
        // Intenta la conexión usando PDO
        $conexion = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ejecuta la consulta SQL para obtener datos de distribuciones y deudas
        $query = "SELECT MONTH(d.fecha) as mes, COUNT(distinct de.id_deuda) as cantidad_morosos
                  FROM distribuciones d
                  LEFT JOIN deudas de ON d.id_distribucion = de.id_distribucion
                  WHERE de.moroso = 1
                  GROUP BY mes";
        
        $stmt = $conexion->prepare($query);
        $stmt->execute();

        // Procesa los resultados y guárdalos en un array
        $morososData = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $morososData[$row['mes']] = $row['cantidad_morosos'];
        }

        // Cierra la conexión
        $conexion = null;

        return $morososData;
    } catch (PDOException $e) {
        // Maneja cualquier error de conexión o consulta
        die("Error de conexión: " . $e->getMessage());
    }
}
?>
