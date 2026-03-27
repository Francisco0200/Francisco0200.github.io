<?php
/** Ubicación: php/det-venta-elimina.php */
header("Content-Type: application/json; charset=utf-8");
try {
    $bd = new PDO("sqlite:" . __DIR__ . "/srvcompras.db");
    $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $prodId = $_POST["prodId"] ?? null;

    if ($prodId) {
        $stmt = $bd->query("SELECT VENT_ID FROM VENTA WHERE VENT_EN_CAPTURA = 1 LIMIT 1");
        $venta = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($venta) {
            $del = $bd->prepare("DELETE FROM DET_VENTA WHERE VENT_ID = ? AND PROD_ID = ?");
            $del->execute([$venta["VENT_ID"], $prodId]);
        }
    }
    echo json_encode(["status" => "ok"]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>