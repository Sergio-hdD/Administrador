<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Teacher;
use App\Repository\CourseRepository;
use App\Repository\TeacherRepository;
use App\Service\ConsultasService;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/teacher_course")
 */
class TeacherCourseController extends AbstractController
{

    /**
     * @Route("/", name="app_teacher_course_index", methods={"GET"})
     */
    public function index(CourseRepository $courseRepository,TeacherRepository $teacherRepository, ConsultasService $consultasService): Response
    {
        $teachers_courses = array();

        foreach ($teacherRepository->findAll() as $teacher) {
            $courses = array();
            foreach ($consultasService->traerIdCursosDelProfesor($teacher->getId()) as $container_course_id) {
                array_push($courses, $courseRepository->findOneBy([ 'id' => intval($container_course_id['course_id']) ]));
            }
            array_push($teachers_courses, $teacher);
            array_push($teachers_courses, $courses);

        }

        return $this->render('teacher_course/index.html.twig', [
            'teachers_courses' => $teachers_courses,
        ]);
    }

    /**
     * @Route("/new", name="app_new_teacher_course", methods={"GET", "POST"})
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
