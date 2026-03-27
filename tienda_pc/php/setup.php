<?php
/** Ubicación: php/setup.php */
// Activamos todos los errores para ver qué pasa
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🛠️ Generador de Base de Datos</h1>";

$archivoDb = __DIR__ . "/srvcompras.db";

// 1. Verificar Permisos de la Carpeta
if (!is_writable(__DIR__)) {
    die("<h2 style='color:red'>❌ ERROR CRÍTICO:</h2>
         <p>Tu carpeta <code>" . __DIR__ . "</code> no tiene permisos de escritura.</p>
         <p>Windows no deja que AppServ cree archivos aquí.</p>
         <p><b>Solución:</b> Mueve tu proyecto a <code>C:/AppServ/www/</code> directamente o ejecuta AppServ como Administrador.</p>");
}

// 2. Borrar base vieja si existe
if (file_exists($archivoDb)) {
    unlink($archivoDb);
    echo "<p>🗑️ Archivo antiguo eliminado.</p>";
}

try {
    // 3. Crear la conexión (Esto crea el archivo físico)
    $pdo = new PDO("sqlite:" . $archivoDb);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p>✅ Archivo <code>srvcompras.db</code> creado exitosamente.</p>";

    // 4. Crear Tablas
    $sqlProductos = "CREATE TABLE IF NOT EXISTS PRODUCTO (
      PROD_ID INTEGER PRIMARY KEY AUTOINCREMENT,
      PROD_NOMBRE TEXT NOT NULL,
      PROD_EXISTENCIAS INTEGER NOT NULL,
      PROD_PRECIO REAL NOT NULL
    );";
    
    $sqlVentas = "CREATE TABLE IF NOT EXISTS VENTA (
      VENT_ID INTEGER PRIMARY KEY AUTOINCREMENT,
      VENT_EN_CAPTURA INTEGER NOT NULL 
    );"; // 1=Sí, 0=No

    $sqlDetalle = "CREATE TABLE IF NOT EXISTS DET_VENTA (
      VENT_ID INTEGER NOT NULL,
      PROD_ID INTEGER NOT NULL,
      DTV_CANTIDAD REAL NOT NULL,
      DTV_PRECIO REAL NOT NULL,
      PRIMARY KEY (VENT_ID, PROD_ID),
      FOREIGN KEY (VENT_ID) REFERENCES VENTA(VENT_ID),
      FOREIGN KEY (PROD_ID) REFERENCES PRODUCTO(PROD_ID)
    );";

    $pdo->exec($sqlProductos);
    $pdo->exec($sqlVentas);
    $pdo->exec($sqlDetalle);
    echo "<p>✅ Tablas creadas (Producto, Venta, Detalle).</p>";

    // 5. Insertar Hardware (Los productos)
    $stmt = $pdo->query("SELECT COUNT(*) FROM PRODUCTO");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO PRODUCTO (PROD_NOMBRE, PROD_EXISTENCIAS, PROD_PRECIO) VALUES
            ('Tarjeta NVIDIA RTX 4060', 10, 6500.00),
            ('Procesador AMD Ryzen 7 5700G', 15, 3200.00),
            ('Monitor Gamer 144Hz', 8, 4500.00),
            ('Teclado Mecánico RGB', 20, 800.00),
            ('Mouse Logitech G502', 25, 900.00),
            ('Memoria RAM 16GB DDR4', 30, 1100.00)
        ");
        echo "<p>✅ 6 Productos de Hardware insertados.</p>";
    }

    echo "<h2 style='color:green'>🎉 ¡TODO LISTO!</h2>";
    echo "<p>La base de datos se generó correctamente. <a href='../index.html'>VOLVER A LA TIENDA</a></p>";

} catch (PDOException $e) {
    echo "<h2 style='color:red'>☠️ ERROR DE BASE DE DATOS:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>