<?php

function ventaEnCapturaAgrega(\PDO $bd)
{
 $bd->exec("INSERT INTO VENTA (VENT_EN_CAPTURA) VALUES (1)");
}
