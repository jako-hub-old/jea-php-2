<?php 
$dirConfiguracion = realpath('aplicacion/configuraciones/configuracion.php');
$dirSistema = realpath('base/Sistema.php');
require_once $dirSistema;
\Base\Sistema::crearAplicacion($dirConfiguracion)->iniciar();