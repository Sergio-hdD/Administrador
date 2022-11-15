<?php

namespace App\Controller;

use App\Entity\TeacherExam;
use App\Form\TeacherExamType;
use App\Repository\TeacherExamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/teacher_exam")
 */
class TeacherExamController extends AbstractController
{
    /**
     * @Route("/", name="app_teacher_exam_index", methods={"GET"})
     */
    public function index(TeacherExamRepository $teacherExamRepository): Response
    {
        return $this->render('teacher_exam/index.html.twig', [
            'teacher_exams' => $teacherExamRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_teacher_exam_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TeacherExamRepository $teacherExamRepository): Response
    {
        $teacherExam = new TeacherExam();
        $form = $this->createForm(TeacherExamType::class, $teacherExam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teacherExamRepository->add($teacherExam, true);

            return $this->redirectToRoute('app_teacher_exam_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('teacher_exam/new.html.twig', [
            'teacher_exam' => $teacherExam,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_teacher_exam_show", methods={"GET"})
     */
    public function show(TeacherExam $teacherExam): Response
    {
        return $this->render('teacher_exam/show.html.twig', [
            'teacher_exam' => $teacherExam,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_teacher_exam_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TeacherExam $teacherExam, TeacherExamRepository $teacherExamRepository): Response
    {
        $form = $this->createForm(TeacherExamType::class, $teacherExam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teacherExamRepository->add($teacherExam, true);

            return $this->redirectToRoute('app_teacher_exam_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('teacher_exam/edit.html.twig', [
            'teacher_exam' => $teacherExam,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_teacher_exam_delete", methods={"POST"})
     */
    public function delete(Request $request, TeacherExam $teacherExam, TeacherExamRepository $teacherExamRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teacherExam->getId(), $request->request->get('_token'))) {
            $teacherExamRepository->remove($teacherExam, true);
        }

        return $this->redirectToRoute('app_teacher_exam_index', [], Response::HTTP_SEE_OTHER);
    }
}
