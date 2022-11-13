<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use App\Service\SoapService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/student")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class StudentController extends AbstractController
{
    /**
     * @Route("/", name="app_student_index", methods={"GET"})
     */
    public function index(StudentRepository $studentRepository): Response
    {
        return $this->render('student/index.html.twig', [
            'students' => $studentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_student_new", methods={"GET", "POST"})
     */
    public function new(Request $request, StudentRepository $studentRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                
            $soapService = new SoapService();
            $response = $soapService->userInsert_soap($form, Student::STR_USER_TYPE);

            if (!$response->Resultado) {             
                $this->addFlash('massage', $response->Mensaje);
                
                return $this->renderForm('student/new.html.twig', [
                    'student' => $student,
                    'form' => $form,
                ]);
            }

            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/new.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_student_show", methods={"GET"})
     */
    public function show(Student $student): Response
    {
        return $this->render('student/show.html.twig', [
            'student' => $student,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_student_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Student $student): Response
    {
        $form = $this->createFormBuilder()
        ->add('dni', TextType::class, ['attr' => ['placeholder' => 'DNI', 'value' => $student->getDni()], 'label' => 'DNI', 'required' => true])
        ->add('email', TextType::class, ['attr' => ['placeholder' => 'Email', 'value' => $student->getEmail()], 'label' => 'Email', 'required' => true])
        ->add('lastname', TextType::class, ['attr' => ['placeholder' => 'Lastname', 'value' => $student->getLastname()], 'label' => 'Lastname', 'required' => true])
        ->add('name', TextType::class, ['attr' => ['placeholder' => 'Name', 'value' => $student->getName()], 'label' => 'Name', 'required' => true])
        ->add('phone', TextType::class, ['attr' => ['placeholder' => 'Phone', 'value' => $student->getPhone()], 'label' => 'Phone', 'required' => true])
        ->getForm();
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $soapService = new SoapService();
            $response = $soapService->userUpdate_soap($student->getId(), $form, Student::STR_USER_TYPE);

            if (!$response->Resultado) {             
                $this->addFlash('massage', $response->Mensaje);

                return $this->renderForm('student/edit.html.twig', [
                    'admin' => $student,
                    'form' => $form,
                ]);
            }

            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/edit.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_student_delete", methods={"POST"})
     */
    public function delete(Request $request, Student $student, StudentRepository $studentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$student->getId(), $request->request->get('_token'))) {
            
            $soapService = new SoapService();
            $response = $soapService->userDelete_soap($student->getId(), Student::STR_USER_TYPE);

            if (!$response->Resultado) {             
                $this->addFlash('massage', $response->Mensaje);
            }

        }

        return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
    }
}
