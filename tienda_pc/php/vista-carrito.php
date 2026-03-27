<?php
/** Ubicación: php/vista-carrito.php */
header("Content-Type: application/json; charset=utf-8");
try {
    $bd = new PDO("sqlite:" . __DIR__ . "/srvcompras.db");
    $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta
    $sql = "SELECT P.PROD_ID, P.PROD_NOMBRE, P.PROD_PRECIO, D.DTV_CANTIDAD, (D.DTV_CANTIDAD * P.PROD_PRECIO) as SUBTOTAL 
            FROM DET_VENTA D
            JOIN VENTA V ON V.VENT_ID = D.VENT_ID
            JOIN PRODUCTO P ON P.PROD_ID = D.PROD_ID
            WHERE V.VENT_EN_CAPTURA = 1";
    
    $stmt = $bd->query($sql);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = "";
    $total = 0;

    foreach($items as $item) {
        $id = $item["PROD_ID"];
        $nombre = htmlentities($item["PROD_NOMBRE"]);
        $precioUnit = number_format($item["PROD_PRECIO"], 2);
        $cant = $item["DTV_CANTIDAD"];
        $sub = number_format($item["SUBTOTAL"], 2);
        $total += $item["SUBTOTAL"];

        // --- DISEÑO EN FILA (ROW) ---
        // Aquí ponemos el INPUT number y el BOTÓN eliminar directamente
        $html .= "
        <div class='carrito-item'>
            <div class='info'>
                <span class='nombre'>$nombre</span>
                <span class='precio-unit'>($$precioUnit c/u)</span>
            </div>
            
            <div class='controles'>
                <input type='number' min='1' value='$cant' 
                       onchange='actualizarItem($id, this.value)' 
                       class='input-cant'>
                
                <span class='subtotal'>$$sub</span>
                
                <button onclick='eliminarItem($id)' class='btn-delete'>🗑️</button>
            </div>
        </div>";
    }

    if ($html === "") {
        $html = "<div style='text-align:center; padding:40px; color:#777;'>Tu carrito está vacío 🕸️</div>";
        // Si está vacío, ocultamos el botón de pagar (esto se maneja en el JS)
        $total = 0; 
    }

    // Enviamos también el TOTAL GLOBAL para mostrarlo en grande
    echo json_encode([
        "detalles" => ["innerHTML" => $html],
        "totalGlobal" => number_format($total, 2)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>