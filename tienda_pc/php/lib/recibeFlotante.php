<?php

require_once __DIR__ . "/recibeTexto.php";

/**
 * Devuelve el valor decimal de un parámetro (que
 * puede tener fracciones) enviado al servidor por
 * medio de GET, POST o cookie.
 * 
 * Si el parámetro no se recibe, devuekve false
 * 
 * Si se recibe una cadena vacía, se devuelve null.
 * 
 * Si parámetro no se puede convertir a decimal, se genera
 * un error.
 */
function recibeFlotante(string $parametro): false|null|float
{
 $valor = recibeTexto($parametro);
 if ($valor === false) {
  return false;
 } else {
  $valor = trim($valor);
  if ($valor === "") {
   return null;
  } else {
   return (float) $valor;
  }
 }
}
