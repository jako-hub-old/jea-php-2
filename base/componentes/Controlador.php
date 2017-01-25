<?php

namespace Base\Componentes;

use \Base\Sistema;
use Exception;

abstract class Controlador extends ComponenteAplicacion {

    const CONTENIDO = 'Content-Type';
    const JSON = 'application/json';

    protected $accion;
    protected $contenido;
    protected $plantilla = 'basica';
    protected $titulo;
    protected $dirVistas;
    protected $nombreAccion;
    protected $get;
    protected $post;
    protected $migas = [];
    protected $opciones = [];

    public function __construct($ID) {
        $this->ID = $ID;
        $this->dirVistas = Sistema::resolverRuta("aplicacion.vistas." . lcfirst($ID));
    }

    public function inicializar() {
        
    }

    public function iniciar() {
        
    }

    public static function getInstancia($directorio, $ID, $modulo = false) {
        Sistema::importar($directorio, false);
        $namespace = "Aplicacion\\Controladores\\" . $ID;
        if (!class_exists($namespace)) {
            throw new Exception("No existe la clase $ID", 1);
        }
        $obj = new $namespace($ID);
        if (!$obj instanceof Controlador) {
            throw new Exception("La clase controlador $ID debe ser instancia de Controlador", 1);
        }
        return $obj;
    }

}