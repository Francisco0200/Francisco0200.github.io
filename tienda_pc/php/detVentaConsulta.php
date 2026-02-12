<?php

function detVentaConsulta(\PDO $bd, int $ventaId)
{
 $stmt = $bd->prepare(
  "SELECT
    DV.PROD_ID,
    P.PROD_NOMBRE,
    P.PROD_EXISTENCIAS,
    P.PROD_PRECIO,
    DV.DTV_CANTIDAD,
    DV.DTV_PRECIO
   FROM DET_VENTA DV, PRODUCTO P
   WHERE
    DV.PROD_ID = P.PROD_ID
    AND DV.VENT_ID = :VENT_ID
   ORDER BY P.PROD_NOMBRE"
 );
 $stmt->execute([":VENT_ID" => $ventaId]);
 $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
 return $lista;
}
