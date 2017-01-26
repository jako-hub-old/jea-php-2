<?php

/**
 * Aquí se encuentran las configuraciones que aplican solo para la aplicación
 */
return [
    'nombre' => 'Jako\'s CRM',
    
    'manejadores' => [
       'Ruta' => ['manejadores', '\Base\Manejadores'], 
       'Recurso' => ['manejadores', '\Base\Manejadores'], 
       'Sesion' => ['manejadores', '\Base\Manejadores'],
       'Personalizado' => ['aplicacion.manejadores', '\Aplicacion\Manejadores'],
    ]
];
