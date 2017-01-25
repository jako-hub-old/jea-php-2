<?php

/**
 * Esta clase representa la aplicación que se ejecuta en el sistema, 
 * es quien inicializa, los componentes y módulos de la aplicación 
 * @package base
 * @author Jorge Alejandro Quiroz Serna (Jako) <alejo.jko@gmail.com>
 * @version 1.0.0
 * @copyright (c) 2017 jakolab
 */

namespace Base;

Sistema::importar("componentesSis.ComponenteAplicacion");
Sistema::importar("componentesSis.Controlador");
Sistema::importar("componentesSis.Modulo");

final class Aplicacion {

    /**
     * Identificador único de la aplicación
     * @var [type]
     */
    private $ID;

    /**
     * Nombre de la aplicación
     * @var [type]
     */
    private $nombre;

    /**
     * Conjunto de caracteres usado por la aplicación
     * @var string
     */
    private $charset = 'utf-8';

    /**
     * Zona horaria de la aplicación
     * @var string
     */
    private $timezone = 'America/Bogota';

    /**
     * Directorio de configuraciones
     * @var string
     */
    private $dirConfg;

    /**
     * Configuraciones de la aplicación
     * @var Array
     */
    private $configuraciones;

    /**
     * Directorio de plantillas
     * @var string
     */
    private $dirPlantillas;

    /**
     * Representa el tema de la aplicación
     * @var \Base\Componentes\Tema
     */
    private $tema;

    /**
     * Ruta solicitada para la aplicación
     * @var string
     */
    private $ruta;

    /**
     * Url base de la aplicación
     * @var string
     */
    private $urlBase;

    /**
     * Ruta base de la aplicación
     * @var string
     */
    private $rutaBase;

    /**
     * Versión de la aplicación
     * @var string
     */
    private $version = '1.0.0';

    /**
     * Nombre del componente (controlador o módulo) invocado
     * @var string
     */
    private $componente;

    /**
     * Nombre del módulo solicitado
     * @var string
     */
    private $modulo;

    /**
     * Componente (módulo o controlador) que se ejecuta en la aplicación
     * @var \Base\Componentes\ComponenteAplicacion
     */
    private $comPrincipal;

    /*     * ****************************************************************************
     * 									Manejadores								  * 
     * **************************************************************************** */
    private $MRecursos;
    private $MRutas;
    private $MError;
    private $MExcepcion;
    private $MSesion;

    private function __construct($dirConfg) {
        $this->dirConfg = $dirConfg;
        $this->construirRuta();
    }

    /**
     * Retorna la única instancia de la aplicación que corre en el sistema
     * @param  string $configuracion ruta dónde se encuentra el archivo de configuración del sistema
     * @return Base\Aplicacion                
     */
    public static function getInstancia($configuracion) {
        static $instancia = null;
        if ($instancia === null){ $instancia = new Aplicacion($configuracion); }
        return $instancia;
    }

    public function inicializar() {        
        $this->cargarComponentes();
        $this->comPrincipal->inicializar();
    }

    private function construirRuta() {
        # filtramos el contenido de GET
        $g = filter_input_array(INPUT_POST);
        $r = isset($g['r']) ? $g['r'] : 'principal/panel';
        
        $partes = explode("/", $r);
        $this->componente = isset($partes[0]) ? $partes[0] : 'principal';
        $this->accion = isset($partes[1]) ? $partes[1] : 'panel';
    }

    public function iniciar() {
        $this->comPrincipal->iniciar();
    }   

    private function cargarComponentes() {
        $nombre = ucfirst($this->componente);
        # Buscamos si se intenta acceder a un controlador o a un módulo
        if (Sistema::existeArchivo("controladores.$nombre")) {
            $this->cargarControlador($nombre);
        } else if (Sistema::existeArchivo("modulos.$nombre", true, false)) {
            $this->cargarModulo($nombre);
        }
    }

    private function cargarControlador($nombre) {
        $dirControlador = Sistema::resolverRuta("controladores.$nombre", true);
        $this->comPrincipal = \Base\Componentes\Controlador::getInstancia($dirControlador, $nombre);
    }

    private function cargarModulo($nombre) {
        $dirModulo = Sistema::resolverRuta("modulos.$nombre.$nombre", true);
        $this->comPrincipal = \Base\ComponentesModulo::getInstancia($dirModulo, $nombre);
    }

}
