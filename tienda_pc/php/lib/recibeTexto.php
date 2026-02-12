<?php
/** Ubicación: php/lib/recibeTexto.php */
function recibeTexto($parametro)
{
    if (isset($_REQUEST[$parametro])) {
        return $_REQUEST[$parametro];
    } else {
        return false;
    }
}