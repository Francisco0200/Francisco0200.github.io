<?php

function productoBusca(\PDO $bd, int $prodId)
{
$stmt = $bd->prepare("SELECT * FROM PRODUCTO WHERE PROD_ID = :PROD_ID");
$stmt->execute([":PROD_ID" => $prodId]);
$modelo = $stmt->fetch(PDO::FETCH_ASSOC);
 return $modelo;
}
