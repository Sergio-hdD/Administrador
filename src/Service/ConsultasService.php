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


    public function traerIdCursosDelProfesor( $id_teacher )
    {
        $sql = "SELECT tc.course_id FROM teacher_course tc 
                WHERE tc.teacher_id='" . $id_teacher . "'";

        return $this->Myquery($sql);
    }

}
