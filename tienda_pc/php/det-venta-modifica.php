<?php
/** Ubicación: php/det-venta-modifica.php */
header("Content-Type: application/json; charset=utf-8");
try {
    $bd = new PDO("sqlite:" . __DIR__ . "/srvcompras.db");
    $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recibimos datos por POST
    $prodId = $_POST["prodId"] ?? null; // Ojo: en modifica.html se llama prodId
    $cantidad = $_POST["cantidad"] ?? null;

    if (!$prodId) throw new Exception("Falta ID del producto.");

    // Buscamos el carrito activo
    $stmt = $bd->query("SELECT VENT_ID FROM VENTA WHERE VENT_EN_CAPTURA = 1 LIMIT 1");
    $venta = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($venta) {
        $ventId = $venta["VENT_ID"];
        
        // Actualizamos la cantidad
        $update = $bd->prepare("UPDATE DET_VENTA SET DTV_CANTIDAD = ? WHERE VENT_ID = ? AND PROD_ID = ?");
        $update->execute([$cantidad, $ventId, $prodId]);
    }

    echo json_encode(["status" => "ok"]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>