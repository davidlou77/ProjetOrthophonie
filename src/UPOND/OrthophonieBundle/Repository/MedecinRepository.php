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
        $medecin = $query->geResult();
        return $medecin;
    }
}
