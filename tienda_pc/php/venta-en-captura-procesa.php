<?php
/** Ubicación: php/venta-en-captura-procesa.php */
header("Content-Type: application/json; charset=utf-8");
try {
    // 1. Conexión Directa
    $bd = new PDO("sqlite:" . __DIR__ . "/srvcompras.db");
    $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $bd->beginTransaction(); // Iniciamos operación segura

    // 2. Buscar el carrito activo
    $stmt = $bd->query("SELECT VENT_ID FROM VENTA WHERE VENT_EN_CAPTURA = 1 LIMIT 1");
    $venta = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$venta) {
        throw new Exception("No hay ninguna venta abierta.");
    }
    $ventId = $venta["VENT_ID"];

    // 3. Obtener qué productos se compraron
    $stmtDet = $bd->prepare("SELECT PROD_ID, DTV_CANTIDAD FROM DET_VENTA WHERE VENT_ID = ?");
    $stmtDet->execute([$ventId]);
    $detalles = $stmtDet->fetchAll(PDO::FETCH_ASSOC);

    // 4. RESTAR EL STOCK (Actualizar inventario)
    $stmtUpdate = $bd->prepare("UPDATE PRODUCTO SET PROD_EXISTENCIAS = PROD_EXISTENCIAS - ? WHERE PROD_ID = ?");
    
    foreach ($detalles as $item) {
        $stmtUpdate->execute([$item["DTV_CANTIDAD"], $item["PROD_ID"]]);
    }

    // 5. CERRAR LA VENTA (Ya no es captura, es venta final)
    $bd->exec("UPDATE VENTA SET VENT_EN_CAPTURA = 0 WHERE VENT_ID = $ventId");
    
    $bd->commit(); // Guardar cambios
    echo json_encode(["status" => "ok"]);

} catch (Exception $e) {
    if ($bd->inTransaction()) $bd->rollBack(); // Deshacer si hay error
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>