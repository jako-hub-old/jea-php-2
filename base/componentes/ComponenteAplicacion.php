<?php
/**
 * Esta clase es la plantilla para cada componente de la aplicación 
 * contiene los atributos y métodos necesarios para que un componente 
 * funcione correctamente dentro de la aplicación
 * @package base.componentes
 * @author Jorge Alejandro Quiroz Serna (jako) <alejo.jko@gmail.com>
 * @version 1.0.0
 * @copyright (c) 2017, jakolab 
 */
namespace Base\Componentes;

abstract class ComponenteAplicacion {
    /**
     * Identificador único del componente
     * @var string 
     */
    protected $ID;
    
    /**
     * Función que debe ser implementada por los componentes para inicializar
     * los elementos necesarios para el funcionamiento del componente
     */
    abstract function inicializar();
    
    /**
     * Función que debe ser implementada para ejecutar la lógica del componente
     */
    abstract function iniciar();
    
    /**
     * Asigna el Identificador único del componente
     * @param string $ID
     */
    public function setID($ID){
        $this->ID = $ID;
    }
    
}
