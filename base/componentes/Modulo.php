<?php

namespace Base\Componentes;

use Base\Sistema;
use Exception;

abstract class Modulo extends ComponenteAplicacion {

    protected $dirUbicacion;
    protected $controlador;
    protected $diseno = 'diseno';

    public function __construct($id, $ruta) {
        $this->ID = $id;
        $this->ruta = $ruta;
    }

    public function inicializar() {
        
    }

    public function iniciar() {
        
    }

    public static function getInstancia($directorio, $ID) {
        Sistema::importar($directorio, false);
        $namespace = "Aplicacion\\Modulos\\$ID";
        if (!class_exists($namespace))
            throw new Exception("No existe la clase principal del módulo", 1);

        $dirModulo = Sistema::resolverRuta("modulos.$ID");
        $obj = new $namespace($ID, $dirModulo);

        if (!$obj instanceof Modulo)
            throw new Exception("La clase módulo $ID debe ser instancia de Modulo", 1);

        return $obj;
    }

}
