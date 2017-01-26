<?php

/**
 * Aquí se declaran cada uno de los alias que harán parte de la aplicación.
 * Un alias es un nombre corto que se le da a una ruta específica de la 
 * aplicación. 
 * @package base.utilidades
 * @author Jorge Alejandro Quiroz Serna (jako) <alejo.jko@gmail.com>
 * @version 1.0.0
 * @copyright 2017, jakop
 */
use \Base\Sistema;

$dirBase = realpath(Sistema::getUbicacion() . '/..');

return [
    'raiz' => realpath($dirBase),
    'base' => realpath($dirBase . DS . 'base'),
    'componentes' => realpath($dirBase . DS . 'base' . DS . 'componentes'),
    'manejadores' => realpath($dirBase . DS . 'base' . DS . 'manejadores'),
    'aplicacion' => realpath($dirBase . DS . 'aplicacion'),
    'recursos' => realpath($dirBase . DS . 'recursos'),
    'modulos' => realpath($dirBase . DS . 'aplicacion' . DS . 'modulos'),
    'controladores' => realpath($dirBase . DS . 'aplicacion' . DS . 'controladores'),
    'vistas' => realpath($dirBase . DS . 'aplicacion' . DS . 'vistas'),
    'modelos' => realpath($dirBase . DS . 'aplicacion' . DS . 'modelos'),
];
