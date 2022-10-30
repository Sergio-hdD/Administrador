<?php

namespace App\Controller;

use App\Entity\InscriptionCourse;
use App\Form\InscriptionCourseType;
use App\Repository\InscriptionCourseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inscription/course")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class InscriptionCourseController extends AbstractController
{
    /**
     * @Route("/", name="app_inscription_course_index", methods={"GET"})
     */
    public function index(InscriptionCourseRepository $inscriptionCourseRepository): Response
    {
        return $this->render('inscription_course/index.html.twig', [
            'inscription_courses' => $inscriptionCourseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_inscription_course_new", methods={"GET", "POST"})
     */
    public function new(Request $request, InscriptionCourseRepository $inscriptionCourseRepository): Response
    {
        $inscriptionCourse = new InscriptionCourse();
        $form = $this->createForm(InscriptionCourseType::class, $inscriptionCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inscriptionCourseRepository->add($inscriptionCourse, true);

            return $this->redirectToRoute('app_inscription_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inscription_course/new.html.twig', [
            'inscription_course' => $inscriptionCourse,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_inscription_course_show", methods={"GET"})
     */
    public function show(InscriptionCourse $inscriptionCourse): Response
    {
        return $this->render('inscription_course/show.html.twig', [
            'inscription_course' => $inscriptionCourse,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_inscription_course_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, InscriptionCourse $inscriptionCourse, InscriptionCourseRepository $inscriptionCourseRepository): Response
    {
        $form = $this->createForm(InscriptionCourseType::class, $inscriptionCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inscriptionCourseRepository->add($inscriptionCourse, true);

            return $this->redirectToRoute('app_inscription_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inscription_course/edit.html.twig', [
            'inscription_course' => $inscriptionCourse,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_inscription_course_delete", methods={"POST"})
     */
    public function delete(Request $request, InscriptionCourse $inscriptionCourse, InscriptionCourseRepository $inscriptionCourseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inscriptionCourse->getId(), $request->request->get('_token'))) {
            $inscriptionCourseRepository->remove($inscriptionCourse, true);
        }

        return $this->redirectToRoute('app_inscription_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
