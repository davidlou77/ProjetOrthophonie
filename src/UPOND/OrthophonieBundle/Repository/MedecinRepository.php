<?php

namespace UPOND\OrthophonieBundle\Repository;
use Proxies\__CG__\UPOND\OrthophonieBundle\Entity\Utilisateur;

/**
 * MedecinRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MedecinRepository extends \Doctrine\ORM\EntityRepository
{
    public function SeekUserByName($nom){
       $em=$this
           ->getEntityManager();
        // on récupere l'utilisateur
        $query = $em->createQuery(  
            "SELECT u
                FROM UPONDOrthophonieBundle:Utilisateur u
                WHERE u.nom = :nom"
        );
        $query->setParameter('nom', $nom);
        $utilisateur = $query->getResult();
        return $utilisateur;

    }

    public function SeekUserByFirstName($prenom){
        $em=$this
            ->getEntityManager();
        // on récupere l'utilisateur
        $query = $em->createQuery(
            "SELECT u
                FROM UPONDOrthophonieBundle:Utilisateur u
                WHERE u.nom = :prenom"
        );
        $query->setParameter('prenom', $prenom);
        $utilisateur = $query->getResult();
        return $utilisateur;

    }
    public function SeekUserByNameAndFirstName($nom,$prenom){
        $em=$this
            ->getEntityManager();
        // on récupere l'utilisateur
        $query = $em->createQuery(
            "SELECT u
                FROM UPONDOrthophonieBundle:Utilisateur u
                WHERE u.nom = :nom AND u.prenom= :prenom"
        );
        $query->setParameter('nom', $nom);
        $query->setParameter('prenom', $prenom);
        $utilisateur = $query->geResult();
        return $utilisateur;

    }
    public function SeekMedecinByID($id){
        $em=$this
            ->getEntityManager();
        $query = $em->createQuery(
            "SELECT m
                FROM UPONDOrthophonieBundle:Medecin m
                WHERE m.utilisateur= :id"
        );
        $query->setParameter('id', $id);
        $medecin = $query->getResult();
        return $medecin;
    }

    public function findPatientByMedecin($id){
            $em = $this
                ->getEntityManager();

            $query = $em->createQuery(
                "SELECT p
             FROM UPONDOrthophonieBundle:Patient p
             LEFT JOIN p.medecins m
             WHERE m=:idMedecin
             "
            );
            $query->setParameter('idMedecin',$id);
            $patient=$query->getResult();
            return $patient;
    }

    public function findOnePatientOfMedecin($idMed,$idPat){
        $em = $this
            ->getEntityManager();

        $query = $em->createQuery(
            "SELECT p
             FROM UPONDOrthophonieBundle:Patient p
             LEFT JOIN p.medecins m
             WHERE m=:idMedecin AND p.idPatient=:idPatient
             "
        );
        $query->setParameter('idMedecin',$idMed);
        $query->setParameter('idPatient',$idPat);
        $patient=$query->getResult();
        return $patient;
    }
    public function findIdMedecinByRef($utilisateur){
        $em=$this
            ->getEntityManager();
        $query = $em->createQuery(
            "SELECT m
                FROM UPONDOrthophonieBundle:Medecin m
                JOIN m.utilisateur u
                WHERE u = :user1"
        );
        $query->setParameter('user1', $utilisateur);
        $idMedecin = $query->getSingleResult();
        return $idMedecin;
    }
}
