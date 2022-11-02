<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Teacher;
use App\Repository\CourseRepository;
use App\Repository\TeacherRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeacherCourseController extends AbstractController
{
    /**
     * @Route("/teacher/course/new", name="app_new_teacher_course", methods={"GET", "POST"})
     */
    public function login(Request $request, TeacherRepository $teacherRepository, CourseRepository $courseRepository): Response
    {

        $form = $this->createFormBuilder()
            ->add('teacher', EntityType::class, [
                'class' => Teacher::class,
                'placeholder' => 'Seleccione un profesor',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.id', 'ASC');
                }
            ])
            ->add('course', EntityType::class, [
                'class' => Course::class,
                'placeholder' => 'Seleccione un curso',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.id', 'ASC');
                }
            ])
            ->add('submit', SubmitType::class, [ 'attr' => ['class' => 'btn btn-primary w-100',], 'label' => '<i class="fas fa-sync fa-spin"></i> Guardar', 'label_html' => true,]) 
            ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teacher = $form->getData()['teacher'];
            $course = $form->getData()['course'];
            
            $estaElProfesorEnElCurso = false; 
            foreach ($course->getTeachers() as $teacher_in_course) {
                if($teacher == $teacher_in_course){
                    $estaElProfesorEnElCurso = true; break;
                }
            }
            
            if($estaElProfesorEnElCurso){ //Si ya hay relacion
                $this->addFlash('massage_danger', 'Esta asignación curso-profesor ya fue realizada');
                return $this->redirectToRoute('app_new_teacher_course', [], Response::HTTP_SEE_OTHER);
            }
                $teacher->addCourse($course);
                $course->addTeacher($teacher);
                $teacherRepository->add($teacher, true);
                $courseRepository->add($course, true);

                $this->addFlash('massage_success', 'Asignacion realizada corectamente');
                return $this->redirectToRoute('app_new_teacher_course', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('teacher_course/new_teacher_course.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
