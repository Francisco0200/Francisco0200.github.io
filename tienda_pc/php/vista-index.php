<?php
/** Ubicación: php/vista-index.php */
header("Content-Type: application/json; charset=utf-8");
error_reporting(0); // Evitar errores visuales

try {
    $bd = new PDO("sqlite:" . __DIR__ . "/srvcompras.db");
    $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $bd->query("SELECT * FROM PRODUCTO");
    $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- IMÁGENES CORREGIDAS ---
    $imagenes = [
        1 => "https://images.unsplash.com/photo-1591488320449-011701bb6704?auto=format&fit=crop&w=500&q=80", // GPU
        // CAMBIO AQUÍ: Nueva imagen de procesador que sí funciona
        2 => "https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?auto=format&fit=crop&w=500&q=80", // CPU Ryzen
        3 => "https://images.unsplash.com/photo-1547394765-185e1e68f34e?auto=format&fit=crop&w=500&q=80", // Monitor
        4 => "https://images.unsplash.com/photo-1595225476474-87563907a212?auto=format&fit=crop&w=500&q=80", // Teclado
        5 => "https://images.unsplash.com/photo-1527814050087-3793815479db?auto=format&fit=crop&w=500&q=80", // Mouse
        6 => "https://images.unsplash.com/photo-1562976540-1502c2145186?auto=format&fit=crop&w=500&q=80"  // RAM
    ];
    $imgDefault = "https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=500&q=80";

    $htmlRenderizado = "";

    if (count($lista) === 0) {
        $htmlRenderizado = "<div style='grid-column:1/-1;text-align:center;color:white;'><h3>Inventario Vacío</h3></div>";
    } else {
        foreach ($lista as $prod) {
            $id = htmlentities($prod["PROD_ID"]);
            $nombre = htmlentities($prod["PROD_NOMBRE"]);
            $precio = number_format($prod["PROD_PRECIO"], 2);
            $stock = htmlentities($prod["PROD_EXISTENCIAS"]);
            $urlImg = $imagenes[$prod["PROD_ID"]] ?? $imgDefault;

            $htmlRenderizado .= "
            <div class='tarjeta-producto'>
                <img src='$urlImg' alt='$nombre'>
                <div class='card-body'>
                    <dt title='$nombre'>$nombre</dt>
                    <dd>
                        <div class='price-stock'>
                            <span class='price-tag'>$$precio</span>
                            <span>Stock: $stock</span>
                        </div>
                        <a href='agrega.html?id=$id' class='btn'>AGREGAR AL CARRITO</a>
                    </dd>
                </div>
            </div>";
        }
    }
    echo json_encode(["lista" => ["innerHTML" => $htmlRenderizado]]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>