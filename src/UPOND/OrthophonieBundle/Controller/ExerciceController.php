<?php
/**
 * Created by PhpStorm.
 * User: davidlou
 * Date: 02/05/2016
 * Time: 14:36
 */

namespace UPOND\OrthophonieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use UPOND\OrthophonieBundle\Entity\Multimedia;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use UPOND\OrthophonieBundle\Repository\PartieRepository;
use UPOND\OrthophonieBundle\Repository\PatientRepository;


class ExerciceController extends Controller
{
    public function selectPartieAction(Request $request)
    {

        // On crée le FormBuilder grâce au service form factory
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class);

        // On ajoute les champs que l'on veut à notre formulaire
        $formBuilder
            ->add('Partie', 'entity', array(
                'class'    => 'UPONDOrthophonieBundle:Partie',
                'property' => 'idPartieAndDateCreation',
                'multiple' => false,
                'query_builder' => function(PartieRepository $er) {
                    $em = $this
                        ->getDoctrine()
                        ->getManager();
                    $repositoryPatient = $em
                        ->getRepository('UPONDOrthophonieBundle:Patient')
                    ;
                    $utilisateur = $this->container->get('security.context')->getToken()->getUser();
                    $patient = $repositoryPatient->findOneByUtilisateur($utilisateur);

                    return $er->createQueryBuilder('partie')
                        ->where('partie.patient = :patient')
                        ->setParameter('patient', $patient);

                    },
            ))
            ->add('Démarrer', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-success')));

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();


        // si on valide le formulaire
        if ($form->handleRequest($request)->isValid()) {

            $em = $this
                ->getDoctrine()
                ->getManager();

            $repository = $em
                ->getRepository('UPONDOrthophonieBundle:Strategie')
            ;

            $donneesForm = $form->getData();
            $session = $request->getSession();
            // stocker une variable de session pour la partie
            $session->set('partie', $donneesForm['Partie']);
            
            // on stocke aussi la stratégie utilisée en récupérant l'entité stratégie en fonction du nom
            $strategie = $repository->findOneByNom($request->attributes->get('strategie'));
            $session->set('strategie', $strategie);

            return $this->redirect($this->generateUrl('upond_orthophonie_exercice'));

        }

        return $this->render('UPONDOrthophonieBundle:Partie:selectPartie.html.twig', array( 'form' => $form->createView()));
    }

    public function afficherExerciceAction(Request $request)
    {
        // on recupere l'exercice associée a la strategie, la phase, le niveau et la partie
        $em = $this->getDoctrine()->getManager();
        $ExerciceRepository = $em->getRepository('UPONDOrthophonieBundle:Exercice');
        $EtapeRepository = $em->getRepository('UPONDOrthophonieBundle:Etape');
        $session = $request->getSession();
        $exercice = $ExerciceRepository->getExerciceByPartiePhaseStrategieNiveau($session->get('partie'), $session->get('phase'), $session->get('strategie'), $session->get('niveau'));

        // on recupere l'etape courante de l'exercice
        $etapeCourante = $exercice->getEtapeCourante();
        // on recupere le multimedia de l'etape courante
        $multimedia = $etapeCourante->getMultimedia();
        // on recupere le numero de l'etape de l'etape courante
        $numeroEtape = $etapeCourante->getNumEtape();

        // On crée le FormBuilder grâce au service form factory
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class);

        // On ajoute les champs que l'on veut à notre formulaire
        $formBuilder
            ->add('BonneReponse', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-success')))
            ->add('MauvaiseReponse', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-danger')));

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();

        // si on clique un des deux boutons de validation, on ajoute la bonne/mauvaise réponse dans la base
        if ($form->handleRequest($request)->isValid()) {

            // si c'est le bouton "Bonne réponse", on passe a l'etape suivante
            if ($form->get('BonneReponse')->isClicked()) {
                // on a 7 etapes maximum
                if($numeroEtape<7) {
                    // on recupere l'etape suivante
                    $etape_suivante = $EtapeRepository->getEtapeByExerciceAndNumEtape($exercice, $numeroEtape + 1);
                    $exercice->setEtapeCourante($etape_suivante);
                    // on met a jour l'exercice en cours
                    $em->persist($exercice);
                    $em->flush();
                    // on recupere le multimedia de l'etape suivante
                    $multimedia = $etape_suivante->getMultimedia();
                }
                $request->getSession()->getFlashBag()->add('reponse', 'Bonne réponse.');
                return $this->render('UPONDOrthophonieBundle:Exercice:exercice.html.twig', array('multimedia' => $multimedia, 'form' => $form->createView()));
            }

            // si c'est le bouton "Mauvaise réponse", on passe a l'etape précédente
            if ($form->get('MauvaiseReponse')->isClicked()) {

                // si on est la premiere etape et que le patient donne une mauvaise réponse, on reste a la premiere etape
                if($numeroEtape==1) {
                    // on recupere l'etape précédente (la premiere)
                    $etape_precedente = $EtapeRepository->getEtapeByExerciceAndNumEtape($exercice, $numeroEtape);
                    $exercice->setEtapeCourante($etape_precedente);
                    // on met à jour l'exercice en cours
                    $em->persist($exercice);
                    $em->flush();
                    // on recupere le multimedia de l'etape précédente
                    $multimedia = $etape_precedente->getMultimedia();
                }
                else
                {
                    // on recupere l'etape précédente de l'exercice
                    $etape_precedente = $EtapeRepository->getEtapeByExerciceAndNumEtape($exercice, $numeroEtape - 1);
                    $exercice->setEtapeCourante($etape_precedente);
                    // on met a jour l'exercice en cours
                    $em->persist($exercice);
                    $em->flush();
                    // on recupere le multimedia de l'etape précédente
                    $multimedia = $etape_precedente->getMultimedia();
                }

                $request->getSession()->getFlashBag()->add('reponse', 'Mauvaise réponse.');
                return $this->render('UPONDOrthophonieBundle:Exercice:exercice.html.twig', array('multimedia' => $multimedia, 'form' => $form->createView()));
            }
        }

        return $this->render('UPONDOrthophonieBundle:Exercice:exercice.html.twig', array('multimedia' => $multimedia, 'form' => $form->createView()));
    }

}