<?php
header("Content-Type: application/json; charset=utf-8");
try {
    // --- CONEXIÓN DIRECTA ---
    $bd = new PDO("sqlite:" . __DIR__ . "/srvcompras.db");
    $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recibimos el ID
    $id = $_GET["id"] ?? null;
    if (!$id) throw new Exception("Falta el ID del producto");

    // Buscamos el producto
    $stmt = $bd->prepare("SELECT * FROM PRODUCTO WHERE PROD_ID = ?");
    $stmt->execute([$id]);
    $prod = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prod) throw new Exception("Producto no encontrado");

    // Preparamos los datos para el formulario
    $nombre = htmlentities($prod["PROD_NOMBRE"]);
    $precio = number_format($prod["PROD_PRECIO"], 2);
    
    // Enviamos al HTML
    echo json_encode([
        "id" => ["value" => $prod["PROD_ID"]],
        "producto" => ["value" => $nombre],
        "precio" => ["value" => "$" . $precio]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>