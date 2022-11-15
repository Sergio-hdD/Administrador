<?php

namespace App\Controller;

use App\Service\ConsultasService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/planilla")
 */
class PlanillaPdfController extends AbstractController
{
    /**
     * @Route("/", name="planilla_cuatrimestral", methods={"GET"})
     */
    public function planillaCuatrimestral(): Response
    {
        return $this->render('planilla_pdf/show_curso_por_turno.html.twig', [
        ]);
    }

    /**
     * @Route("/pdf/cuatrimestral/{turno}", name="planilla_cuatrimestral_pdf", methods={"GET"})
     */
    public function pdfCursosPorTurno($turno, ConsultasService $consultasService): Response
    {
        $years_carrer = ['Primero', 'Segundo', 'Tercero', 'Cuarto', 'Quinto'];

        $reporteDeCursada = array();
        foreach ($years_carrer as $year) {
            foreach ($consultasService->reporteDeCursadaPorTurno($turno) as $reporte) {
                if($year == $reporte['year_carrer'] ){
                    array_push($reporteDeCursada, $reporte);
                }
            }
        }

        return $this->render('planilla_pdf/pdf_curso_por_turno.html.twig', [
            'reporteDeCursada' => $reporteDeCursada,
        ]);
    }

    /**
     * @Route("/cursos/{turno}", name="get_cursos_por_turno", methods={"GET"})
     */
    public function getCursosPorTurno($turno, ConsultasService $consultasService): Response
    {
        return new JsonResponse(
            $consultasService->reporteDeCursadaPorTurno($turno)
        );
    }


    /**
     * @Route("/examen", name="planilla_examen", methods={"GET"})
     */
    public function planillaExamen(): Response
    {
        return $this->render('planilla_pdf/show_examen_entre_fechas.html.twig', [
        ]);
    }

    /**
     * @Route("/examen/{primeraFecha}/{segundaFecha}", name="get_examen_entre_fechas", methods={"GET"})
     */
    public function getExamenPorFecha($primeraFecha, $segundaFecha, ConsultasService $consultasService): Response
    {

        return new JsonResponse(
            $consultasService->reporteDeExamen($primeraFecha, $segundaFecha)
        );
    }


    /**
     * @Route("/pdf/examen/{primeraFecha}/{segundaFecha}", name="planilla_examen_pdf", methods={"GET"})
     */
    public function pdfExamenPorFecha($primeraFecha, $segundaFecha, ConsultasService $consultasService): Response
    {

        return $this->render('planilla_pdf/pdf_examen_entre_fechas.html.twig', [
            'reporteDeCursada' => $consultasService->reporteDeExamen($primeraFecha, $segundaFecha)
        ]);
    }

}
