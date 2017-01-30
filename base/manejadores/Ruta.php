<?php

/**
 * Esta clase es la encargada de gesionar todas las rutas de la aplicación, 
 * Interpreta, genera y almacena las rutas de la aplicación
 * @package paquete
 * @author Jorge Alejandro Quiroz Serna (jako) <alejo.jko@gmail.com>
 * @version 1.0.0
 * @copyright (c) 2017, jakolab
 */

namespace Base\Manejadores;

use Base\Sistema;
use Exception;

class Ruta extends Manejador{
    /**
     * Rutas registradas en la aplicación
     * @var array 
     */
    private static $rutas;
    /**
     * Ruta que se invoca desde el navegador
     * @var string 
     */
    private static $rutaInvocada;
    /**
     * Ruta registrada que corresponde a la ruta invocada desde el navegador
     * @var string 
     */
    private static $rutaActual = null;
    
    private static $parametrosControlador = [];
    
    /**
     * Método de envio para la ruta actual 
     * POST | GET
     * @var string 
     */    
    private static $metodoRuta = null;
    /**
     * Argumentos que serán enviados al componente que se instancia
     * @var array 
     */
    private $argumentosComponente = [];
    
            
    /**
     * Función que será llamada en caso de que la ruta sea de tipo callback
     * @var callback 
     */
    private $fnRetorno;
      
    
    public function inicializar() {
        if(self::$rutaActual == null){
            throw new Exception("La ruta no se encuentra definida");
        }
        $this->resolverArgumentos();
        $parametros = self::$rutas[self::$rutaActual];
        $this->prepararDespacho($parametros);
    }
    
    public function iniciar() {
        
    }    
    
    /**
     * Prepara el envio del controlador a la aplicación
     * @param array $parametros
     */
    private function prepararDespacho($parametros){
        if(isset($parametros['namespace'])){ 
            $this->prepararControlador($parametros);
        } else {
            call_user_func($parametros['llamado']);
        }
    }
    
    /**
     * Instancia el controlador definido en la ruta
     * @param array $config
     * @throws Exception
     */
    private function prepararControlador($config) {
        $ruta = str_replace('\\', '.', $config['namespace']);        
        $nombreCtrl = basename($config['namespace']);
        Sistema::importar(lcfirst($ruta));
        $instancia = new $config['namespace']($nombreCtrl);
        if(!$instancia instanceof \Base\Componentes\Controlador){
            throw new Exception("El controlador '" . $config['namespace'] . "' no es valido");
        }
        Sistema::aplicacion()->setControladorActivo($instancia, $config['llamado'], $this->argumentosComponente);
        $instancia = null;
    }
    
    /**
     * Obtiene los argumentos invocados por la url, estos argumentos serán 
     * pasados posteriormente al componente instanciado
     */
    private function resolverArgumentos(){
        $coincidencias = [];
        preg_match_all('|\{([A-Za-z0-9]+?)\}|', self::$rutaActual, $coincidencias);        
        $noParametros = preg_replace('/' . implode('\/|', $coincidencias[0]) . '/', '', self::$rutaActual);
        $valoresRuta = str_replace($noParametros, '', self::$rutaInvocada);
        $claves = $coincidencias[1];
        $valores = explode('/', $valoresRuta);
        if(count($claves) > 0) {
            $this->argumentosComponente = array_combine($claves, $valores);        
        }
    }
    
    /**
     * Esta función setea la ruta actual invocada para la aplicación
     */
    public static function setRutaInvocada(){
        $g = filter_input_array(INPUT_GET, $_GET);
        self::$rutaInvocada = isset($g['r'])? $g['r'] :  '/';
    }    
    
    /**
     * Esta función guarda las rutas de la aplicación
     * @param string $ruta
     * @param mixed $parametros
     * @param string $metodo método de envio de la ruta
     */
    private static function registrarRuta($ruta, $parametros, $metodo){        
        if(is_callable($parametros)){
            self::$rutas[$ruta] = ['llamado' => $parametros];
        } else {
            self::$rutas[$ruta] = [
                'namespace' => $parametros[0],
                'llamado' => $parametros[1],
            ];            
        }
        self::$rutas[$ruta]['metodo'] = $metodo;
        self::resolverRuta($ruta, $metodo);
    }
    
    /**
     * Valida si la ruta registrada coincide con la ruta invocada en el navegador, 
     * de ser así, se almacenará cual es la ruta actual y toda su configuración
     * @param string $ruta
     * @param string $metodo
     * @return boolean
     */
    private static function resolverRuta($ruta, $metodo){
        # Si ya se encontró la ruta actual se evita seguir procesando
        if(self::$rutaActual !== null) { return false; }
        
        $noResuelve = strpos($ruta, '{') === false;
        # si la ruta no tiene parametros la procesamos normal
        if($noResuelve && $ruta == self::$rutaInvocada){
            self::$rutaActual = $ruta;
            self::$metodoRuta = $metodo;
        } else if($noResuelve == false){
            $rutaPatron = preg_replace('|\{[a-zA-Z0-9]+\}|', '([0-9a-zA-Z]+)', $ruta);
            $patron = '|^' . $rutaPatron . '$|';
            $encontrado = preg_match($patron, self::$rutaInvocada) == 1;
            if($encontrado){ 
                self::$rutaActual = $ruta; 
                self::$metodoRuta = $metodo;
            }
        }
    }
    
    /**
     * Registra una ruta de tipo get
     * @param string $ruta
     * @param mixed $parametros
     */
    public static function get($ruta, $parametros){
        self::registrarRuta($ruta, $parametros, 'get');        
    }
    
    /**
     * Registra una ruta de tipo post
     * @param string $ruta
     * @param mixed $parametros
     */
    public static function post($ruta, $parametros){
        self::registrarRuta($ruta, $parametros, 'post');
    }
}
