<?php

namespace Aplicacion\Controladores;

use Base\Componentes\Controlador;

class Principal extends Controlador {
    
    public function inicializar() {
        echo "Inicializar controlador<br>";
    }
    
    public function iniciar() {
        echo "Iniciar controlador<br>";
    }
    
    public function inicio(){
        echo "Inicio...<br>";
    }
    
    public function saludo(){
        echo "Hola mundo!!<br>";
    }
    
    public function porPost(){
        echo "llamado por post";
    }
    
    public function porId($id){
        echo "id: $id";
    }
}
