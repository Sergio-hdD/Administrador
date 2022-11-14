<?php

namespace App\Controller;

use App\Entity\Student;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="app_dashboard")
     */
    public function index(): Response
    {
        if($this->getUser()){
            if($this->getUser() instanceof Student){
                return $this->redirectToRoute('app_student_change_password', [], Response::HTTP_SEE_OTHER);
            }
        } else {
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'dashboard',
        ]);
    }
}
