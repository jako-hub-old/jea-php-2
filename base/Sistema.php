<?php

/**
 * Esta clase representa el sistema o entorno bajo el cual corre la aplicación, 
 * inicializa toda la lógica base y ofrece herramientas comunes para la aplicación
 * @package base
 * @author Jorge Alejandro Quiroz Serna (Jako) <alejo.jko@gmail.com>
 * @version 1.0.0
 * @copyright (c) 2017 jakolab
 */

namespace Base;

use Exception;

final class Sistema {

    /**
     * Array que contiene los alias de la aplicación, un alias permite acceder a una ruta del sistema
     * de una manera más sencilla.
     * @var Array
     */
    private static $alias;

    /**
     * Única instancia de la aplicación corriendo en el sistema.
     * @var \Base\Aplicacion
     */
    private static $aplicacion = null;

    /**
     * Directorio donde se encuentra actualmente el sistema
     * @var string
     */
    private static $dirSistema;

    /**
     * Versión actual del sistema
     * @var string
     */
    private static $version = '1.0.0';

    private function __construct() {
        
    }

    /**
     * Carga las utilidades básicas del sistema, instancia 
     * la aplicación que se usará en todo el sistema
     * @param  string $configuracion ruta en la cual se encuentra el archivo de 
     *                               configuraciones de la aplicación
     * @return \Base\Aplicacion      Instancia única de la aplicación
     */
    public static function crearAplicacion() {
        self::$dirSistema = realpath(__DIR__);
        self::cargarUtilidades();
        self::importar("base.Aplicacion");

        if (self::$aplicacion == null) {
            self::$aplicacion = \Base\Aplicacion::getInstancia();
            self::$aplicacion->inicializar();
        }

        return self::$aplicacion;
    }
    
    /**
     * Retorna la única instancia de la aplicación que corre en el sistema
     * @return \Base\Aplicacion
     */
    public static function aplicacion(){
        return self::$aplicacion;
    }
    
    /**
     * Retorna la única instancia de la aplicación que corre en el sistema
     * [Alias - aplicacion]
     * @return \Base\Aplicacion
     */
    public static function ap(){
        return self::$aplicacion;
    }

    /**
     * Permite importar archivos desde cualquier directorio de la aplicación, para realizar 
     * esta importación el método recibe la ruta donde se encuentra el archivo. La ruta debe estar 
     * <b>en el siguiente formato: alias.carpeta1.carpeta2.archivo</b>
     * Dónde el alias es una palabra reservada que apunta a una ubicación especifíca de la aplicación 
     * seguido de las carpetas en las cuales se desea buscar y por último el archivo sin extensión 
     * (El sistema solo importa archivos con extensión php)
     * Alias por defecto en el sistema: 
     * <ul>
     *  <li>raiz :				 Ruta base de la aplicación</li>
     * 	<li>base :				 Directorio base del core</li>
     * 	<li>componentesSis :	 Componentes del sistema</li>
     * 	<li>aplicacion :		 Ruta base de la aplicación</li>
     * 	<li>recursos :			 Ruta de los recursos de la aplicación</li>
     * 	<li>modulos :			 Ruta de los módulos</li>
     * 	<li>controladores :		 Ruta de los controladores</li>
     * 	<li>vistas :			 Ruta de las vistas</li>
     * 	<li>modelos :			 Ruta de los modelos</li>
     * </ul>
     * @param  mixed  $recursoAImportar ruta del archivo que se desea importar o array con las rutas
     * @param  boolean $alias      si se usa alias o ruta absoluta
     */
    public static function importar($recursoAImportar, $alias = true) {
        if(is_string($recursoAImportar)){
            return self::importarSimple($recursoAImportar, $alias);
        } else if(is_array($recursoAImportar)){
            return self::importarMultiple($recursoAImportar, $alias);
        }
    }
    
    /**
     * Importa un archivo usando notación de puntos, retorna el contenido del 
     * archivo (en caso de que el archivo retorne algún contenido)
     * @param string $dirArchivo
     * @param  $alias true : Si se usa notación de puntos, false : si se usa ruta absoluta
     * @return mixed
     */
    public static function importarArchivo($dirArchivo, $alias = true){
        if ($alias) { $dirArchivo = self::resolverRuta($dirArchivo, true); }
        if (!file_exists($dirArchivo)) { return false; }
        return include $dirArchivo;
    }
    
    /**
     * Esta función permite 
     * @param type $dirArchivo
     * @param type $alias
     * @return boolean
     */
    private static function importarSimple($dirArchivo, $alias){
        if ($alias) { $dirArchivo = self::resolverRuta($dirArchivo, true); }
        if (!file_exists($dirArchivo)) { return false; }
        return (include_once($dirArchivo)) === 1;
    }
    
    private static function importarMultiple($directorios, $alias){
        $error = false;
        foreach($directorios AS $dir){
            if(!self::importarSimple($dir, $alias)){ 
                throw new Exception("No se pudo importar '$dir'");
            }
        }
        return $error;
    }

    /**
     * Convierte una ruta en notación de puntos (alias.carpeta.carpeta) en una ruta absoluta
     * Alias por defecto en el sistema: 
     * <ul>
     *  <li>raiz :				 Ruta base de la aplicación</li>
     * 	<li>base :				 Directorio base del core</li>
     * 	<li>componentesSis :	 Componentes del sistema</li>
     * 	<li>aplicacion :		 Ruta base de la aplicación</li>
     * 	<li>recursos :			 Ruta de los recursos de la aplicación</li>
     * 	<li>modulos :			 Ruta de los módulos</li>
     * 	<li>controladores :		 Ruta de los controladores</li>
     * 	<li>vistas :			 Ruta de las vistas</li>
     * 	<li>modelos :			 Ruta de los modelos</li>
     * </ul>
     * @param  string  $ruta    ruta en notación del puntos (no incluye archivos)
     * @param  boolean $archivo si se incluye el archivo en la ruta (automáticamente se añade la extensión .php)
     * @return string           el resultado de convertir la ruta
     */
    public static function resolverRuta($ruta, $archivo = false) {
        $partes = explode('.', $ruta);
        $rutaFinal = $partes[0]; # por defecto la ruta final es igual a la primera porción de la ruta inicial
        $ultPorcion = $partes[count($partes) - 1]; # la última porción almacena la última carpeta o el nombre de la clase
        # si la primera porción es un alias registrado, procedemos a obtener la ruta del alias
        if (isset(self::$alias[$rutaFinal])) {
            $rutaFinal = self::$alias[$rutaFinal];
            unset($partes[0]);
        }
        $limite = count($partes);
        for ($i = 1; $i < $limite; $i ++) { $rutaFinal .= DS . $partes[$i]; }
        $rutaFinal .= ($ultPorcion !== '' ? DS . $ultPorcion : '');
        return $rutaFinal . ($archivo ? '.php' : '');
    }

    /**
     * Valida si un archivo existe existe, se permite enviar una ruta absoluta 
     * o en notación de puntos, adicionalmente, se puede indicar si se busca un archivo
     * o una carpeta
     * @param  string  $archivo ruta del archivo en notación de puntos
     * @param  boolean $alias   si la ruta utiliza alias
     * @param  boolean $ext     <true> : si se trata de un archivo <false> : si se busca una carpeta
     * @return [type]           [description]
     */
    public static function existeArchivo($archivo, $alias = true, $ext = true) {
        if ($alias) { $ruta = self::resolverRuta($archivo, $ext); } 
        else { $ruta = $archivo; }
        return file_exists($ruta);
    }

    /**
     * Carga las utilidades, globales y alias del sistema
     * @return [type] [description]
     */
    private static function cargarUtilidades() {
        $dirBase = self::$dirSistema . '/utilidades';
        $globales = realpath("$dirBase/Globales.php");
        $alias = realpath("$dirBase/Alias.php");
        if (!file_exists($globales)) { throw new Exception("Error al intentar cargar las globales", 1); }
        if (!file_exists($alias)) { throw new Exception("Error al cargar los alias", 1); }
        require_once($globales);
        self::$alias = include $alias;
    }

    /**
     * Retorna la ruta actual del sistema
     * @return string
     */
    public static function getUbicacion() {
        return self::$dirSistema;
    }

}
