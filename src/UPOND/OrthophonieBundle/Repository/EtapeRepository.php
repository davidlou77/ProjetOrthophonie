<?php

namespace UPOND\OrthophonieBundle\Repository;

/**
 * EtapeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EtapeRepository extends \Doctrine\ORM\EntityRepository
{
    public function getIdEtapesFromPhase($partie, $phase)
    {
        $em = $this
            ->getEntityManager();
        $queryEtape = $em->createQuery(
            'SELECT et.idEtape
            FROM UPONDOrthophonieBundle:Exercice e
            JOIN e.etapes et
            WHERE e.partie = :partie AND e.phase = :phase
            GROUP BY et.idEtape'
        );
        $queryEtape->setParameters(array('partie' => $partie,
            'phase' => $phase));

        $idEtapes = $queryEtape->getResult();
//foreach ($idQuestions as $value)
//{
//    echo $value['idQuestion'];
//}
        /* $repositoryQuestion = $em
             ->getRepository('UPONDOrthophonieBundle:Question')
         ;
         // on prend un id aléatoire parmi les résultats
         $idQuestion = array_rand($idQuestions, count($idQuestions));
         // on récupere l'entité de l'ID
         $listQuestions = $repositoryQuestion->findByIdQuestion($idQuestion);
         return $listQuestions;
        */
        return $idEtapes;
    }
    
    
    public function getEtapesByArrayOfIdEtape($arrayEtape)
    {

        $em = $this
            ->getEntityManager();
        // on récupere l'entité de l'ID
        $queryEtapes = $em->createQuery(
            'SELECT e
                FROM UPONDOrthophonieBundle:Etape e
                WHERE e.idEtape IN (:arrayIdEtapes)'
        );
        $queryEtapes->setParameter('arrayIdEtapes', $arrayEtape);
        $listEtapes = $queryEtapes->getResult();
        return $listEtapes;
    }

    public function getEtapesNotInArrayOfIdEtape($arrayEtape)
    {

        $em = $this
            ->getEntityManager();
        // on récupere l'entité de l'ID
        $queryEtapes = $em->createQuery(
            'SELECT e
                FROM UPONDOrthophonieBundle:Etape e
                WHERE e.idEtape NOT IN (:arrayIdEtapes)'
        );
        $queryEtapes->setParameter('arrayIdEtapes', $arrayEtape);
        $listEtapes = $queryEtapes->getResult();
        return $listEtapes;
    }

    public function getEtapeByExerciceAndNumEtape($exercice, $numEtape)
    {
        $em = $this
            ->getEntityManager();
        $queryEtape = $em->createQuery(
            'SELECT e
            FROM UPONDOrthophonieBundle:Etape e
            WHERE e.exercice = :exercice AND e.numEtape = :numEtape'
        );
        $queryEtape->setParameters(array('exercice' => $exercice,
            'numEtape' => $numEtape));

        $etape = $queryEtape->getOneOrNullResult();
        
        return $etape;
    }

    public function getEtapesByExerciceAndInfNumEtape($exercice, $numEtape)
    {
        $em = $this
            ->getEntityManager();
        $queryEtape = $em->createQuery(
            'SELECT e
            FROM UPONDOrthophonieBundle:Etape e
            WHERE e.exercice = :exercice AND e.numEtape <= :numEtape'
        );
        $queryEtape->setParameters(array('exercice' => $exercice,
            'numEtape' => $numEtape));

        $etapes = $queryEtape->getResult();

        return $etapes;
    }
    public function getlastEtapeByEtapes($etapes)
    {
        $em = $this
            ->getEntityManager();
        $queryEtape = $em->createQuery(
            'SELECT e
            FROM UPONDOrthophonieBundle:Etape e
            WHERE e IN (:etapes)
            ORDER BY e.idEtape DESC'
        );
        $queryEtape->setParameters(array('etapes' => $etapes));
        $queryEtape->setMaxResults(1);
        $etape = $queryEtape->getOneOrNullResult();

        return $etape;
    }
}
