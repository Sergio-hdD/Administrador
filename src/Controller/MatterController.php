<?php

namespace App\Controller;

use App\Entity\Matter;
use App\Form\MatterType;
use App\Repository\MatterRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/matter")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class MatterController extends AbstractController
{
    /**
     * @Route("/", name="app_matter_index", methods={"GET"})
     */
    public function index(MatterRepository $matterRepository): Response
    {
        return $this->render('matter/index.html.twig', [
            'matters' => $matterRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_matter_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MatterRepository $matterRepository): Response
    {
        $matter = new Matter();
        $form = $this->createForm(MatterType::class, $matter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matterRepository->add($matter, true);

            return $this->redirectToRoute('app_matter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('matter/new.html.twig', [
            'matter' => $matter,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_matter_show", methods={"GET"})
     */
    public function show(Matter $matter): Response
    {
        return $this->render('matter/show.html.twig', [
            'matter' => $matter,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_matter_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Matter $matter, MatterRepository $matterRepository): Response
    {
        $form = $this->createForm(MatterType::class, $matter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matterRepository->add($matter, true);

            return $this->redirectToRoute('app_matter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('matter/edit.html.twig', [
            'matter' => $matter,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_matter_delete", methods={"POST"})
     */
    public function delete(Request $request, Matter $matter, MatterRepository $matterRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$matter->getId(), $request->request->get('_token'))) {
            $matterRepository->remove($matter, true);
        }

        return $this->redirectToRoute('app_matter_index', [], Response::HTTP_SEE_OTHER);
    }
}
