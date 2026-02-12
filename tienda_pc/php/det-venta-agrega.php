<?php
/** Ubicación: php/det-venta-agrega.php */
header("Content-Type: application/json; charset=utf-8");
try {
    // 1. CONEXIÓN DIRECTA
    $bd = new PDO("sqlite:" . __DIR__ . "/srvcompras.db");
    $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. RECIBIR DATOS (CORREGIDO: Usamos $_POST estándar)
    // El formulario envía "id" (del input hidden) y "cantidad"
    $prodId = $_POST["id"] ?? null;
    $cantidad = $_POST["cantidad"] ?? null;

    if (!$prodId || !$cantidad) {
        throw new Exception("Faltan datos (ID o Cantidad).");
    }

    // 3. Buscar o crear el carrito activo
    $stmt = $bd->query("SELECT VENT_ID FROM VENTA WHERE VENT_EN_CAPTURA = 1 LIMIT 1");
    $venta = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($venta) {
        $ventId = $venta["VENT_ID"];
    } else {
        $bd->exec("INSERT INTO VENTA (VENT_EN_CAPTURA) VALUES (1)");
        $ventId = $bd->lastInsertId();
    }

    // 4. Obtener precio del producto
    $stmtProd = $bd->prepare("SELECT PROD_PRECIO FROM PRODUCTO WHERE PROD_ID = ?");
    $stmtProd->execute([$prodId]);
    $precio = $stmtProd->fetchColumn();

    if (!$precio) throw new Exception("Producto no encontrado en BD.");

    // 5. Insertar en el detalle (Evitando duplicados)
    // Primero borramos si ya estaba, luego insertamos lo nuevo
    $bd->prepare("DELETE FROM DET_VENTA WHERE VENT_ID = ? AND PROD_ID = ?")->execute([$ventId, $prodId]);
    
    $insert = $bd->prepare("INSERT INTO DET_VENTA (VENT_ID, PROD_ID, DTV_CANTIDAD, DTV_PRECIO) VALUES (?, ?, ?, ?)");
    $insert->execute([$ventId, $prodId, $cantidad, $precio]);

    echo json_encode(["status" => "ok"]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>