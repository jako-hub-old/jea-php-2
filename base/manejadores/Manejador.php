<?php

/**
 * 
 * @package paquete
 * @author Jorge Alejandro Quiroz Serna (jako) <alejo.jko@gmail.com>
 * @version 1.0.0
 * @copyright (c) 2017, jakolab
 */

namespace Base\Manejadores;

use Base\Componentes\ComponenteAplicacion;

abstract class Manejador extends ComponenteAplicacion{
    
    public function __construct($ID) {
        $this->ID = $ID;
    }
    
    public function iniciar() {}
    public function inicializar() {}
}
