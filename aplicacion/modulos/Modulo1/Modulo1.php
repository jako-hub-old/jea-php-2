<?php

namespace Aplicacion\Modulos;

use \Base\Componentes\Modulo;

class Modulo1 extends Modulo {

    public function inicializar() {
        echo "inicializando...";
    }

    public function iniciar() {
        echo "<br>Iniciado...";
    }

}
