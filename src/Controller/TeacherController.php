<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\TeacherType;
use App\Repository\TeacherRepository;
use App\Service\SoapService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/teacher")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class TeacherController extends AbstractController
{
    /**
     * @Route("/", name="app_teacher_index", methods={"GET"})
     */
    public function index(TeacherRepository $teacherRepository): Response
    {
        return $this->render('teacher/index.html.twig', [
            'teachers' => $teacherRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_teacher_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TeacherRepository $teacherRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $teacher = new Teacher();
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                
            $soapService = new SoapService();
            $params = $soapService->createParamsUserInsert($form, Teacher::STR_USER_TYPE);
            $response = $soapService->userInsert_soap($params);

            if (!$response->Resultado) {             
                $this->addFlash('massage', $response->Mensaje);

                return $this->renderForm('teacher/new.html.twig', [
                    'teacher' => $teacher,
                    'form' => $form,
                ]);
            }

            return $this->redirectToRoute('app_teacher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('teacher/new.html.twig', [
            'teacher' => $teacher,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_teacher_show", methods={"GET"})
     */
    public function show(Teacher $teacher): Response
    {
        return $this->render('teacher/show.html.twig', [
            'teacher' => $teacher,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_teacher_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Teacher $teacher): Response
    {
        $form = $this->createFormBuilder()
        ->add('dni', TextType::class, ['attr' => ['placeholder' => 'DNI', 'value' => $teacher->getDni()], 'label' => 'DNI', 'required' => true])
        ->add('email', TextType::class, ['attr' => ['placeholder' => 'Email', 'value' => $teacher->getEmail()], 'label' => 'Email', 'required' => true])
        ->add('lastname', TextType::class, ['attr' => ['placeholder' => 'Lastname', 'value' => $teacher->getLastname()], 'label' => 'Lastname', 'required' => true])
        ->add('name', TextType::class, ['attr' => ['placeholder' => 'Name', 'value' => $teacher->getName()], 'label' => 'Name', 'required' => true])
        ->add('phone', TextType::class, ['attr' => ['placeholder' => 'Phone', 'value' => $teacher->getPhone()], 'label' => 'Phone', 'required' => true])
        ->getForm();
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $soapService = new SoapService();
            $params = $soapService->createParamsUserUpdate($teacher->getId(), $form, Teacher::STR_USER_TYPE);
            $response = $soapService->userUpdate_soap($params);

            if (!$response->Resultado) {             
                $this->addFlash('massage', $response->Mensaje);

                return $this->renderForm('teacher/edit.html.twig', [
                    'admin' => $teacher,
                    'form' => $form,
                ]);
            }

            return $this->redirectToRoute('app_teacher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('teacher/edit.html.twig', [
            'teacher' => $teacher,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_teacher_delete", methods={"POST"})
     */
    public function delete(Request $request, Teacher $teacher): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teacher->getId(), $request->request->get('_token'))) {
            
            $soapService = new SoapService();
            $response = $soapService->userDelete_soap($teacher->getId(), Teacher::STR_USER_TYPE);

            if (!$response->Resultado) {             
                $this->addFlash('massage', $response->Mensaje);
            }

        }

        return $this->redirectToRoute('app_teacher_index', [], Response::HTTP_SEE_OTHER);
    }
}
