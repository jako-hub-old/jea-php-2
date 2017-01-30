<?php
/**
 * @package paquete
 * @author Jorge Alejandro Quiroz Serna (jako) <alejo.jko@gmail.com>
 * @version 1.0.0
 * @copyright (c) 2017, jakolab
 */
use Base\Manejadores\Ruta;

Ruta::get('/', ['Aplicacion\Controladores\Principal', 'inicio']);
Ruta::get('saludar', ['Aplicacion\Controladores\Principal', 'saludo']);
Ruta::post('porPost', ['Aplicacion\Controladores\Principal', 'porPost']);
Ruta::get('{id}/porId', ['Aplicacion\Controladores\Principal', 'porId']);
Ruta::get('{id}/consulta', ['Aplicacion\Controladores\Principal', 'porId']);