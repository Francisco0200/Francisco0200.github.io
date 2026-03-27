<?php

require_once __DIR__ . "/BAD_REQUEST.php";
require_once __DIR__ . "/recibeFlotante.php";
require_once __DIR__ . "/ProblemDetailsException.php";

function recibeFlotanteObligatorio(string $parametro)
{
 $flotante = recibeFlotante($parametro);

 if ($flotante === false)
  throw new ProblemDetailsException([
   "status" => BAD_REQUEST,
   "title" => "Falta el valor $parametro.",
   "type" => "/error/faltavalor.html",
   "detail" => "La solicitud no tiene el valor de $parametro."
  ]);

 if ($flotante === null)
  throw new ProblemDetailsException([
   "status" => BAD_REQUEST,
   "title" => "Campo $parametro en blanco.",
   "type" => "/error/camponumericoenblanco.html",
   "detail" => "Pon un número en el campo $parametro."
  ]);

 return $flotante;
}
