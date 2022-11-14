<?php

namespace App\Controller;


use App\Service\SoapService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/new", name="api_user_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $response= new JsonResponse();
        $data=json_decode($request->getContent());

        $soapService = new SoapService();
        $response_soap = $soapService->userInsert_soap($data);
        
        return $response->setData(
            $response_soap
        );
    }


    /**
     * @Route("/edit", name="api_user_edit", methods={"POST"})
     */
    public function edit(Request $request): Response
    {
        $response= new JsonResponse();
        $data=json_decode($request->getContent());
        
        $soapService = new SoapService();
        $response_soap = $soapService->userUpdate_soap($data);
        
        return $response->setData(
            $response_soap
        );
    }

    
    /**
     * @Route("/change/password", name="api_student_change_password", methods={"POST"})
     */
    public function change_password(Request $request): Response
    {
        $response= new JsonResponse();
        $data=json_decode($request->getContent());
        
        $soapService = new SoapService();
        $response_soap = $soapService->student_change_password($data);
        
        return $response->setData(
            $response_soap
        );
    }
    

    /**
     * @Route("/delete", name="api_user_delete", methods={"POST"})
     */
    public function delete(Request $request): Response
    {
        $response= new JsonResponse();
        $data=json_decode($request->getContent());
        
        $soapService = new SoapService();
        $response_soap = $soapService->userDelete_soap($data->id_user_input, $data->userType_input);

        return $response->setData(
            $response_soap
        );
    }
}
