<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Repository\AdminRepository;
use App\Service\SoapService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_index", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(AdminRepository $adminRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'admins' => $adminRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_new", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function new(Request $request, AdminRepository $adminRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $soapService = new SoapService();
            $params = $soapService->createParamsUserInsert($form, Admin::STR_USER_TYPE);
            $response = $soapService->userInsert_soap($params);

            if (!$response->Resultado) {             
                $this->addFlash('massage', $response->Mensaje);

                return $this->renderForm('admin/new.html.twig', [
                    'admin' => $admin,
                    'form' => $form,
                ]);
            }

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/new.html.twig', [
            'admin' => $admin,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/new/externo", name="app_admin_new_externo", methods={"GET", "POST"})
     */
    public function login(Request $request, AdminRepository $adminRepository, UserPasswordHasherInterface $passwordHasher): Response
    {

        if($this->getUser()){//Si accede a esta ruta y está logueado
            return $this->redirectToRoute('app_admin_new', [], Response::HTTP_SEE_OTHER);
        }
        $admin = new Admin();
        $form = $this->createFormBuilder()
            ->add('dni', TextType::class, ['attr' => ['placeholder' => 'DNI'], 'label' => 'DNI', 'required' => true])
            ->add('email', TextType::class, ['attr' => ['placeholder' => 'Email'], 'label' => 'Email', 'required' => true])
            ->add('lastname', TextType::class, ['attr' => ['placeholder' => 'Lastname'], 'label' => 'Lastname', 'required' => true])
            ->add('name', TextType::class, ['attr' => ['placeholder' => 'Name'], 'label' => 'Name', 'required' => true])
            ->add('password', PasswordType::class, ['attr' => ['placeholder' => 'Password'], 'label' => 'Password', 'required' => true])
            ->add('phone', TextType::class, ['attr' => ['placeholder' => 'Phone'], 'label' => 'Phone', 'required' => true])
            ->add('securityKey', PasswordType::class, ['attr' => ['placeholder' => 'Clave de seguridad'], 'label' => 'Clave de seguridad', 'required' => true])
            ->add('submit', SubmitType::class, [ 'attr' => ['class' => 'btn btn-primary w-100',], 'label' => '<i class="fas fa-sync fa-spin"></i> Guardar', 'label_html' => true,]) 
            ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()['securityKey'] == "1234") {
                
                $soapService = new SoapService();
                $params = $soapService->createParamsUserInsert($form, Admin::STR_USER_TYPE);
                $response = $soapService->userInsert_soap($params);
    
                if (!$response->Resultado) {             
                    $this->addFlash('massage', $response->Mensaje);
    
                    return $this->render('admin/new_externo.html.twig', [
                        'form' => $form->createView()
                    ]);
                }
    
                return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);

            } else {

                $this->addFlash('massage', 'Clave se seguridad incorrecta');                
                return $this->render('admin/new_externo.html.twig', [
                    'form' => $form->createView()
                ]);
            }
        }

        return $this->render('admin/new_externo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_show", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function show(Admin $admin): Response
    {
        return $this->render('admin/show.html.twig', [
            'admin' => $admin,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_edit", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function edit(Request $request, Admin $admin): Response
    {
        $form = $this->createFormBuilder()
        ->add('dni', TextType::class, ['attr' => ['placeholder' => 'DNI', 'value' => $admin->getDni()], 'label' => 'DNI', 'required' => true])
        ->add('email', TextType::class, ['attr' => ['placeholder' => 'Email', 'value' => $admin->getEmail()], 'label' => 'Email', 'required' => true])
        ->add('lastname', TextType::class, ['attr' => ['placeholder' => 'Lastname', 'value' => $admin->getLastname()], 'label' => 'Lastname', 'required' => true])
        ->add('name', TextType::class, ['attr' => ['placeholder' => 'Name', 'value' => $admin->getName()], 'label' => 'Name', 'required' => true])
        ->add('phone', TextType::class, ['attr' => ['placeholder' => 'Phone', 'value' => $admin->getPhone()], 'label' => 'Phone', 'required' => true])
        ->getForm();
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $soapService = new SoapService();
            $params = $soapService->createParamsUserUpdate($admin->getId(), $form, Admin::STR_USER_TYPE);
            $response = $soapService->userUpdate_soap($params);

            if (!$response->Resultado) {             
                $this->addFlash('massage', $response->Mensaje);

                return $this->renderForm('admin/edit.html.twig', [
                    'admin' => $admin,
                    'form' => $form,
                ]);
            }

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/edit.html.twig', [
            'admin' => $admin,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_delete", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function delete(Request $request, Admin $admin): Response
    {
        if ($this->isCsrfTokenValid('delete'.$admin->getId(), $request->request->get('_token'))) {

            $soapService = new SoapService();
            $response = $soapService->userDelete_soap($admin->getId(), Admin::STR_USER_TYPE);

            if (!$response->Resultado) {             
                $this->addFlash('massage', $response->Mensaje);
            }

        }

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
