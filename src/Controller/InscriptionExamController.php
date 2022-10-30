<?php

namespace App\Controller;

use App\Entity\InscriptionExam;
use App\Form\InscriptionExamType;
use App\Repository\InscriptionExamRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inscription/exam")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class InscriptionExamController extends AbstractController
{
    /**
     * @Route("/", name="app_inscription_exam_index", methods={"GET"})
     */
    public function index(InscriptionExamRepository $inscriptionExamRepository): Response
    {
        return $this->render('inscription_exam/index.html.twig', [
            'inscription_exams' => $inscriptionExamRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_inscription_exam_new", methods={"GET", "POST"})
     */
    public function new(Request $request, InscriptionExamRepository $inscriptionExamRepository): Response
    {
        $inscriptionExam = new InscriptionExam();
        $form = $this->createForm(InscriptionExamType::class, $inscriptionExam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inscriptionExamRepository->add($inscriptionExam, true);

            return $this->redirectToRoute('app_inscription_exam_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inscription_exam/new.html.twig', [
            'inscription_exam' => $inscriptionExam,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_inscription_exam_show", methods={"GET"})
     */
    public function show(InscriptionExam $inscriptionExam): Response
    {
        return $this->render('inscription_exam/show.html.twig', [
            'inscription_exam' => $inscriptionExam,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_inscription_exam_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, InscriptionExam $inscriptionExam, InscriptionExamRepository $inscriptionExamRepository): Response
    {
        $form = $this->createForm(InscriptionExamType::class, $inscriptionExam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inscriptionExamRepository->add($inscriptionExam, true);

            return $this->redirectToRoute('app_inscription_exam_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inscription_exam/edit.html.twig', [
            'inscription_exam' => $inscriptionExam,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_inscription_exam_delete", methods={"POST"})
     */
    public function delete(Request $request, InscriptionExam $inscriptionExam, InscriptionExamRepository $inscriptionExamRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inscriptionExam->getId(), $request->request->get('_token'))) {
            $inscriptionExamRepository->remove($inscriptionExam, true);
        }

        return $this->redirectToRoute('app_inscription_exam_index', [], Response::HTTP_SEE_OTHER);
    }
}
