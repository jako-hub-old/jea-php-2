<?php
/**
 * Esta clase es la base para todos los módulos de la aplicación
 * @package base.componentes
 * @author Jorge Alejandro Quiroz Serna (jako) <alejo.jko@gmail.com>
 * @version 1.0.0
 * @copyright (c) 2017, jakolab 
 */
namespace Base\Componentes;

use Base\Sistema;
use Exception;

abstract class Modulo extends ComponenteAplicacion {
    /**
     * directorio donde se localiza el módulo
     * @var string 
     */
    protected $dirUbicacion;
    /**
     * Controlador usado por el módulo
     * @var \Base\Componentes\Controlador 
     */
    protected $controlador;
    /**
     * Nombre del diseño que implementará el módulo en sus vistas
     * @var string 
     */
    protected $diseno = 'diseno';

    public function __construct($id, $ruta) {
        $this->ID = $id;
        $this->ruta = $ruta;
    }

    public function inicializar() {}

    public function iniciar() {}
    
    /**
     * Permite obtener una instancia de un módulo pasando como argumento la ruta
     * en que se encuentra la clase del módulo
     * @param string $directorio directorio donde se localiza el módulo
     * @param string $ID identificador del módulo
     * @return \Base\Componentes\Modulo
     * @throws Exception
     */
    public static function getInstancia($directorio, $ID) {
        Sistema::importar($directorio, false);
        $namespace = "Aplicacion\\Modulos\\$ID";
        if (!class_exists($namespace)){
            throw new Exception("No existe la clase principal del módulo", 1);
        }
        $dirModulo = Sistema::resolverRuta("modulos.$ID");
        $obj = new $namespace($ID, $dirModulo);
        if (!$obj instanceof Modulo){
            throw new Exception("La clase módulo $ID debe ser instancia de Modulo", 1);
        }
        return $obj;
    }

}
