<?php

namespace Base\Componentes;

abstract class ComponenteAplicacion {

    protected $ID;

    abstract function inicializar();

    abstract function iniciar();
}
