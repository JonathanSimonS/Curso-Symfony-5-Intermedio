<?php

namespace App\Controller;

use App\Repository\TareaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    // aplicamos paginacion
    const ELEMENTOS_POR_PAGINA = 10;

    /**
     * propiedad defaults, le da el valor por defecto a la página enrutada
     * 
     * @Route("/{pagina}", 
     *      name="index",
     *      defaults={"pagina":1}, 
     *      requirements={"pagina"="\d+"},
     *      methods={"GET"} )
     */
    public function index(int $pagina,TareaRepository $tareasRepository)
    {
        // ver variable página ---> dump($pagina,);

        return $this->render('index/index.html.twig', [
            'tareas' => $tareasRepository->buscarTodas($pagina, self::ELEMENTOS_POR_PAGINA),
            'pagina' => $pagina,
        ]);
    }
}