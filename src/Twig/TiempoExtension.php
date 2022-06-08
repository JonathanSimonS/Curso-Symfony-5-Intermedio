<?php

namespace App\Twig;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TiempoExtension extends AbstractExtension
{
    const CONFIGURACION = [
        'formato'=>'d/m/Y H:m:s'
    ];
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('tiempo', [$this, 'formatearTiempo']),
        ];
    }

    // public function getFunctions(): array
    // {
    //     return [
    //         new TwigFunction('function_name', [$this, 'doSomething']),
    //     ];
    // }

    // cambiar el formato de fecha con esta extensión
    public function formatearTiempo($fecha, $configuracion = [])
    {
        $configuracion = array_merge(self::CONFIGURACION, $configuracion);
        $fechaActual = new DateTime();
        $fechaFormateada = $fecha->format($configuracion['formato']);
        $diferenciaFechasSegundo = $fechaActual->getTimestamp()- $fecha->getTimestamp();
        
        if ($diferenciaFechasSegundo < 60) {
            $fechaFormateada = 'Creado ahora mismo';
        } elseif ($diferenciaFechasSegundo < 3600) {
            $fechaFormateada = 'Creado recientemente';
        } elseif ($diferenciaFechasSegundo < 10000) {
            $fechaFormateada = 'Creado hace unas horas';
        }
        return $fechaFormateada;
    }
}
