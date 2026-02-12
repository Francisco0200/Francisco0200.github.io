<?php

require_once __DIR__ . "/lib/manejaErrores.php";
require_once __DIR__ . "/lib/recibeEnteroObligatorio.php";
require_once __DIR__ . "/lib/validaEntidadObligatoria.php";
require_once __DIR__ . "/lib/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/productoBusca.php";
require_once __DIR__ . "/ventaEnCapturaBusca.php";

$prodId = recibeEnteroObligatorio("prodId");

$bd = Bd::pdo();

$venta = ventaEnCapturaBusca($bd);
$venta = validaEntidadObligatoria("Venta en captura",  $venta);

$producto = productoBusca($bd, $prodId);
$producto = validaEntidadObligatoria("Producto",  $producto);

$stmt = $bd->prepare(
 "SELECT * FROM DET_VENTA WHERE VENT_ID = :VENT_ID AND PROD_ID = :PROD_ID"
);
$stmt->execute([":VENT_ID" => $venta["VENT_ID"], ":PROD_ID" => $prodId]);
$detVenta = $stmt->fetch(PDO::FETCH_ASSOC);
$detVenta = validaEntidadObligatoria("Detalle de venta",  $detVenta);

devuelveJson([
 "prodId" => ["value" => $prodId],
 "prodNombre" => ["value" => $producto["PROD_NOMBRE"]],
 "precio" => ["value" => "$" . number_format($detVenta["DTV_PRECIO"], 2)],
 "cantidad" => ["valueAsNumber" => $detVenta["DTV_CANTIDAD"]],
]);
