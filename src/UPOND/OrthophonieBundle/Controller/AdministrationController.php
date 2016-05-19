<?php
/**
 * Created by PhpStorm.
 * User: d0ud0o
 * Date: 28/04/2016
 * Time: 23:42
 */

namespace UPOND\OrthophonieBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\SubmitButton;
use UPOND\OrthophonieBundle\Entity\Medecin;
use UPOND\OrthophonieBundle\Entity\Patient;
use UPOND\OrthophonieBundle\Entity\Utilisateur;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use UPOND\OrthophonieBundle\Form\UserSearchType;

class AdministrationController extends Controller
{
    public function patientsAction(){
        return $this->render('UPONDOrthophonieBundle:Administration:patients.html.twig');
    }

    public function medecinsAction(Request $request)
    {
        // on recupere l'exercice associée a la strategie, la phase, le niveau et la partie
        $em = $this->getDoctrine()->getManager();
        $UtilisateurRepository = $em->getRepository('UPONDOrthophonieBundle:Utilisateur');
        $PatientRepository = $em->getRepository('UPONDOrthophonieBundle:Patient');
        $MedecinRepository = $em->getRepository('UPONDOrthophonieBundle:Medecin');

        $listUtilisateurs = $UtilisateurRepository->findAll();
        $listPatients = $PatientRepository->findAll();
        $listMedecins = $MedecinRepository->findAll();

        return $this->render('UPONDOrthophonieBundle:Administration:medecins.html.twig', array('listPatients' => $listPatients, 'listUtilisateurs' => $listUtilisateurs, 'listMedecins' => $listMedecins));
    }

    public function medecinsAjoutAction(Request $request)
    {

        // on recupere l'exercice associée a la strategie, la phase, le niveau et la partie
        $em = $this->getDoctrine()->getManager();
        $UtilisateurRepository = $em->getRepository('UPONDOrthophonieBundle:Utilisateur');
        $PatientRepository = $em->getRepository('UPONDOrthophonieBundle:Patient');
        $MedecinRepository = $em->getRepository('UPONDOrthophonieBundle:Medecin');


        if($request->getMethod() == 'POST') {
            // on recupere l'id utilisateur via le formulaire POST précédent
            $idUtilisateur = $_POST['idUtilisateur'];
            // on recupere l'entité de l'utilisateur
            $user = $UtilisateurRepository->findOneById($idUtilisateur);

            // on supprime le patient
            $patient = $PatientRepository->findOneByUtilisateur($user);
            $em->remove($patient);

            // on l'ajoute dans les medecins
            $medecin = new Medecin();
            $medecin->setUtilisateur($user);
            $em->persist($medecin);
            $em->flush();

        }

        $listUtilisateurs = $UtilisateurRepository->findAll();
        $listPatients = $PatientRepository->findAll();
        $listMedecins = $MedecinRepository->findAll();

        return $this->render('UPONDOrthophonieBundle:Administration:medecins.html.twig', array('listPatients' => $listPatients, 'listUtilisateurs' => $listUtilisateurs, 'listMedecins' => $listMedecins));
    }

    public function medecinsRetirerAction(Request $request)
    {

        // on recupere l'exercice associée a la strategie, la phase, le niveau et la partie
        $em = $this->getDoctrine()->getManager();
        $UtilisateurRepository = $em->getRepository('UPONDOrthophonieBundle:Utilisateur');
        $PatientRepository = $em->getRepository('UPONDOrthophonieBundle:Patient');
        $MedecinRepository = $em->getRepository('UPONDOrthophonieBundle:Medecin');


        if($request->getMethod() == 'POST') {
            // on recupere l'id utilisateur via le formulaire POST précédent
            $idUtilisateur = $_POST['idUtilisateur'];
            // on recupere l'entité de l'utilisateur
            $user = $UtilisateurRepository->findOneById($idUtilisateur);

            // on supprime le patient
            $medecin = $MedecinRepository->findOneByUtilisateur($user);
            $em->remove($medecin);

            // on l'ajoute dans les medecins
            $patient = new Patient();
            $patient->setUtilisateur($user);
            $em->persist($patient);
            $em->flush();

        }

        $listUtilisateurs = $UtilisateurRepository->findAll();
        $listPatients = $PatientRepository->findAll();
        $listMedecins = $MedecinRepository->findAll();

        return $this->render('UPONDOrthophonieBundle:Administration:medecins.html.twig', array('listPatients' => $listPatients, 'listUtilisateurs' => $listUtilisateurs, 'listMedecins' => $listMedecins));
    }

    public function exercicesAction(){
        return $this->render('UPONDOrthophonieBundle:Administration:exercices.html.twig');
    }
}