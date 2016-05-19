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

        //création d'un objet utilisateur
        $PutMedecin = new Utilisateur();

        $form = $this->createForm(UserSearchType::class, $PutMedecin);
        $formAfter=$form;
        $formAfter->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        
        if ($request->getMethod() == 'POST') {
            if ($formAfter->isValid()) {
                $nom = $form['nom']->getData();
                $prenom = $form['prenom']->getData();
                
                $liste_utilisateur = $em->getRepository('UPONDOrthophonieBundle:Medecin')->SeekUserByName($nom);
            } else {
                $liste_utilisateur = null;
            }
            return $this->render('UPONDOrthophonieBundle:Administration:medecins.html.twig', array(
                'form' => $form->createView(),'liste_utilisateur'=>$liste_utilisateur,
            ));
        }
        elseif($request->getMethod() == 'GET'){
                $nom = $form['nom']->getData();
                $prenom = $form['prenom']->getData();
                $id=$em->getRepository('UPONDOrthophonieBundle:Utilisateur')->findIdByNomEtPrenom($nom, $prenom);
                $existe=$em->getRepository('UPONDOrthophonieBundle:Medecin')->SeekMedecinByID($id);
                /*if ($existe==null){
                    $medecin= new Medecin();
                    $medecin->setUtilisateur($id);
                    $em->persist($medecin);
                    $em->flush();
                    return $this->render('UPONDOrthophonieBundle:Administration:medecins.html.twig', array(
                        'form' => $form->createView(),'Médecin crée',
                    ));
                }*/
        }
            return $this->render('UPONDOrthophonieBundle:Administration:medecins.html.twig', array(
                'form' => $form->createView(),
            ));
        //}
        //return null;
    }

    public function exercicesAction(){
        return $this->render('UPONDOrthophonieBundle:Administration:exercices.html.twig');
    }
}