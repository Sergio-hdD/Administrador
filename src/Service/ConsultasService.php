<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ConsultasService
{

    function __construct(EntityManagerInterface $em, SessionInterface $session)
    {
        $this->em = $em;
        $this->conn = $em->getConnection();
    }

    private function Myquery($sql)
    {
        $statement = $this->conn->prepare($sql);
        $resultSet = $statement->executeQuery();
        return $resultSet->fetchAllAssociative();
    }

    public function reporteDeCursada()
    {
        $sql = "SELECT m.name, m.year_carrer, c.turn, c.day_course, c.date_start, c.date_end FROM course c 
                INNER JOIN matter m on m.id=c.matter_id";

        return $this->Myquery($sql);
    }

    public function reporteDeCursadaPorTurno($turno)
    {
        $sql = "SELECT m.name, m.year_carrer, c.turn, c.day_course, c.date_start, c.date_end FROM course c 
                INNER JOIN matter m on m.id=c.matter_id
                WHERE c.turn='" . $turno . "'";

        return $this->Myquery($sql);
    }

    public function traerIdCursosDelProfesor($id_teacher)
    {
        $sql = "SELECT tc.course_id FROM teacher_course tc 
                WHERE tc.teacher_id='" . $id_teacher . "'";

        return $this->Myquery($sql);
    }

    public function reporteDeExamen($fechaDesde, $fechaHasta)
    {
        $sql = "SELECT u.lastname as apellido, u.name as nombre, 
                m.name as materia, m.year_carrer, m.is_first_four_month, 
                e.fecha, e.hora, e.until_change_note 
                FROM exam e INNER JOIN matter m on m.id=e.matter_id
                INNER JOIN teacher_exam te on te.exam_id=e.id
                INNER JOIN teacher t on te.teacher_id=t.id
                INNER JOIN user u on u.id=t.id
                WHERE fecha 
                BETWEEN '" . $fechaDesde . "' AND '" . $fechaHasta . "'";
        return $this->Myquery($sql);
    }
}
