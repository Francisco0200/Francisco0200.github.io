<?php

function ventaEnCapturaBusca(\PDO $bd)
{
 $stmt = $bd->query("SELECT * FROM VENTA WHERE VENT_EN_CAPTURA = 1");
 $venta = $stmt->fetch(PDO::FETCH_ASSOC);
 return $venta;
}
