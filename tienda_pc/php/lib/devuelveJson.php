<?php
function devuelveJson($resultado) {
  header("Content-Type: application/json; charset=utf-8");
  echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
  exit;
}