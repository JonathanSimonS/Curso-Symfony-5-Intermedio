<?php

namespace App\Controller;

use App\Entity\Tarea;
use App\Form\TareaType;
use App\Repository\TareaRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/tarea')]
class TareaController extends AbstractController
{
    #[Route('/listado', name: 'app_tarea_index', methods: ['GET'])]
    public function index(TareaRepository $tareaRepository): Response
    {
        // deniego el acceso si no tiene ROL de ADMIN
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('tarea/index.html.twig', [
            'tareas' => $tareaRepository->findAll(),
        ]);
    }

    #[Route('/nueva', name: 'app_tarea_new', methods: ['GET', 'POST'])]
    public function new(Security $security, Request $request, TareaRepository $tareaRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $tarea = new Tarea();
        $form = $this->createForm(TareaType::class, $tarea);
        
        // Matcheo entre el formulario y la peticion, los une con la tarea 
        // y queda el formulario con los valores enviados
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // añado el usuario a la tarea que se cree, de momento no puede ser nulo
            $tarea->setUsuario($security->getUser());
            $tareaRepository->add($tarea, true);

            return $this->redirectToRoute('app_listado_tarea', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tarea/new.html.twig', [
            'tarea' => $tarea,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tarea_show', methods: ['GET'])]
    public function show(Tarea $tarea): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('tarea/show.html.twig', [
            'tarea' => $tarea,
        ]);
    }

    #[Route('/{id}/editar', name: 'app_tarea_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tarea $tarea, TareaRepository $tareaRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tareaRepository->add($tarea, true);

            return $this->redirectToRoute('app_listado_tarea', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tarea/edit.html.twig', [
            'tarea' => $tarea,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tarea_delete', methods: ['POST'])]
    public function delete(Request $request, Tarea $tarea, TareaRepository $tareaRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($this->isCsrfTokenValid('delete'.$tarea->getId(), $request->request->get('_token'))) {
            $tareaRepository->remove($tarea, true);
        }

        return $this->redirectToRoute('app_listado_tarea', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/tarea/{id}', name: 'finalizar_tarea', methods: ['POST'])]
    public function finalizar(Tarea $tarea, Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // validamos que la petición sea por AJAX
        if ($request->isXmlHttpRequest()) {
            $em    = $doctrine->getManager();
            $tarea->setFinalizada(!$tarea->getFinalizada());
            $em->flush();
            return $this->json([
                'finalizada' => $tarea->getFinalizada()
            ]);
        }
        // todas las devoluciones en controller son de tipo Response
        throw $this->createNotFoundException(); 
    }
}
