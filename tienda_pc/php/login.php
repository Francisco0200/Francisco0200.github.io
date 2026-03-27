<?php
/** Ubicación: php/login.php */
require_once __DIR__ . "/lib/devuelveJson.php";
require_once __DIR__ . "/lib/recibeTextoObligatorio.php";

$usuarioValido = "admin"; 
$passwordValido = "12345";

try {
    $usuario = recibeTextoObligatorio("usuario");
    $password = recibeTextoObligatorio("password");

    if ($usuario === $usuarioValido && $password === $passwordValido) {
        $token = base64_encode("sesion_activa_pc_master");
        devuelveJson(["token" => $token]);
    } else {
        http_response_code(401);
        devuelveJson(["message" => "Usuario o contraseña incorrectos"]);
    }
} catch (Exception $e) {
    http_response_code(400);
    devuelveJson(["message" => $e->getMessage()]);
}