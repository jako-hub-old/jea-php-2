<?php
/**
 * Esta clase es la base para cualquier controlador en la aplicación 
 * contiene los atributos y métodos necesarios para cada controlador en 
 * la aplicación
 * @package base
 * @author Jorge Alejandro Quiroz Serna <alejo.jko@gmail.com>
 * @version 1.0.0
 * @copyright (c) 2017, jakolab
 */

namespace Base\Componentes;

use \Base\Sistema;
use Exception;

abstract class Controlador extends ComponenteAplicacion {
    /***********************************
     *       Tipos de cabecera         *
     ***********************************/
    const CONTENIDO = 'Content-Type';
    const JSON = 'application/json';

    /**
     * Acción invocada para el controlador
     * @var \Base\Componentes\Accion
     */
    protected $accion;
    /**
     * Contenido HTML que se obtiene como resultado de p
     * procesar la vista
     * @var string 
     */
    protected $contenido;
    /**
     * Nombre de la plantilla a implementar
     * @var string 
     */
    protected $plantilla = 'basica';
    /**
     * Título de la vista actual en el controlador
     * @var string 
     */
    protected $titulo;
    /**
     * Directorio de vistas del controlador
     * @var string 
     */
    protected $dirVistas;
    /**
     * Nombre de la acción invocada
     * @var string 
     */
    protected $nombreAccion;
    /**
     * Super global _GET filtrada (solo puede ser usada dentro del controlador)
     * @var array 
     */
    protected $get;
    /**
     * Super global _POST filtrada (solo puede ser usada dentro del controlador)
     * @var array 
     */
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

    /**
     * Instancia un controlador, se debe proporcionar la ruta en que se encuentra
     * el archivo del controlador
     * @param string $directorio Directorio donde se encuentra el controlador
     * @param string $ID Identificador que tendrá el controlador
     * @return \Base\Componentes\Controlador
     * @throws Exception
     */
    public static function getInstancia($directorio, $ID) {
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