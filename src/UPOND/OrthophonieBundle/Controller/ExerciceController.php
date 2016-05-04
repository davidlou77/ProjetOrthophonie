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
            // on créé une variable session pour savoir si on doit afficher toutes les etapes jusqu'a une etape précise, ou bien juste une seule étape
            $session->set('AfficherToutesEtapes', false);
            
            // on stocke aussi la stratégie utilisée en récupérant l'entité stratégie en fonction du nom
            $strategie = $repository->findOneByNom($request->attributes->get('strategie'));
            $session->set('strategie', $strategie);


            if($request->attributes->get('phase') != null) {
                // on stocke la phase (pour la phase de transfert et entrainement niveau 2 uniquement puisque route directement ici)
                $repositoryPhase = $em
                    ->getRepository('UPONDOrthophonieBundle:Phase')
                ;
                $phase = $repositoryPhase->findOneByNom($request->attributes->get('phase'));
                $session->set('phase', $phase);
                $session->set('niveau', $request->attributes->get('niveau'));

            }

            if($session->get('phase')->getNom() != "Apprentissage" && $session->get('niveau') != "1")
            {
                $session->set('afficherSon', false);
            }
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
        $MultimediaRepository = $em->getRepository('UPONDOrthophonieBundle:Multimedia');
        $session = $request->getSession();
        $exercice = $ExerciceRepository->getExerciceByPartiePhaseStrategieNiveau($session->get('partie'), $session->get('phase'), $session->get('strategie'), $session->get('niveau'));


        // on recupere l'etape courante de l'exercice
        $etapeCourante = $exercice->getEtapeCourante();
        // on recupere le multimedia de l'etape courante
        $multimedia = $etapeCourante->getMultimedia();
        $arrayMultimedia = array();
        array_push($arrayMultimedia, $multimedia);
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

                    // si on doit afficher toutes les etapes jusqu'a l'étape courante
                    if ($session->get('AfficherToutesEtapes') == true)
                    {
                        // on a 7 etapes maximum
                        if($numeroEtape<=7) {
                            // on recupere la liste des etapes de l'exercice qui sont inférieurs au numéro de l'etape courante
                            $etapes = $EtapeRepository->getEtapesByExerciceAndInfNumEtape($exercice, $numeroEtape);
                            // on recupere les multimedias de la liste des etapes
                            $multimedias = $MultimediaRepository->getMultimediasInEtapes($etapes);
                            // on les mets dans un tableau
                            $arrayMultimedia = array();
                            foreach ($multimedias as $element) {
                                array_push($arrayMultimedia, $element);
                            }
                            //on indique la derniere etape de notre liste d'etape
                            $derniere_etape_liste = $EtapeRepository->getlastEtapeByEtapes($etapes);
                            $exercice->setEtapeCourante($derniere_etape_liste);
                            // on met a jour l'exercice en cours
                            $em->persist($exercice);
                            $em->flush();

                            $session->set('AfficherToutesEtapes', false);
                        }
                    }
                    // sinon on affiche l'etape courante
                    else {
                        // on a 7 etapes maximum
                        if($numeroEtape<7) {
                            // on recupere l'etape suivante
                            $etape_suivante = $EtapeRepository->getEtapeByExerciceAndNumEtape($exercice, $numeroEtape + 1);
                            $exercice->setEtapeCourante($etape_suivante);
                            // on met a jour l'etape courante (bonne réponse)
                            $etapeCourante->setBonneReponse(true);
                            $em->persist($etapeCourante);
                            $em->flush();
                            // on met a jour l'exercice en cours
                            $em->persist($exercice);
                            $em->flush();
                            $arrayMultimedia = array();
                            // on recupere le multimedia de l'etape suivante
                            $multimedia = $etape_suivante->getMultimedia();
                            array_push($arrayMultimedia, $multimedia);
                            $session->set('AfficherToutesEtapes', true);
                        }
                        // on a 7 etapes maximum, l'exercice est fini
                        if($numeroEtape==7) {
                            // on recupere l'etape suivante
                            $etape_suivante = $EtapeRepository->getEtapeByExerciceAndNumEtape($exercice, $numeroEtape);
                            $exercice->setEtapeCourante($etape_suivante);
                            // on met a jour l'etape courante (bonne réponse)
                            $etapeCourante->setBonneReponse(true);
                            $em->persist($etapeCourante);
                            $em->flush();
                            // on met a jour l'exercice en cours
                            $em->persist($exercice);
                            $em->flush();
                            $session->set('AfficherToutesEtapes', true);
                            echo "Exercice terminé.";
                            return $this->redirect($this->generateUrl('upond_orthophonie_home'));
                        }
                    }

                return $this->render('UPONDOrthophonieBundle:Exercice:exercice.html.twig', array('multimedias' => $arrayMultimedia, 'afficherSon' => $session->get('afficherSon'), 'form' => $form->createView()));
            }

            // si c'est le bouton "Mauvaise réponse", on passe a l'etape précédente
            if ($form->get('MauvaiseReponse')->isClicked()) {

                // si on est la premiere etape et que le patient donne une mauvaise réponse, on reste a la premiere etape

                // si on doit afficher toutes les etapes jusqu'a l'étape courante
                if ($session->get('AfficherToutesEtapes') == true)
                {

                    // on a 7 etapes maximum
                    if($numeroEtape<=7) {
                        // on recupere la liste des etapes de l'exercice qui sont inférieurs au numéro de l'etape courante - 1
                        $etapes = $EtapeRepository->getEtapesByExerciceAndInfNumEtape($exercice, $numeroEtape - 1);
                        // on recupere les multimedias de la liste des etapes
                        $multimedias = $MultimediaRepository->getMultimediasInEtapes($etapes);
                        // on les met dans un tableau
                        $arrayMultimedia = array();
                        foreach ($multimedias as $element) {
                            array_push($arrayMultimedia, $element);
                        }
                        //on indique la derniere etape de notre liste d'etape
                        $derniere_etape_liste = $EtapeRepository->getlastEtapeByEtapes($etapes);
                        
                        $exercice->setEtapeCourante($derniere_etape_liste);
                        // on met a jour l'exercice en cours
                        $em->persist($exercice);
                        $em->flush();

                        $session->set('AfficherToutesEtapes', false);
                    }
                }
                // sinon on affiche l'etape courante
                else {
                    // on a 7 etapes maximum
                    if ($numeroEtape <= 7 && $numeroEtape>1) {
                        // on recupere l'etape précédente
                        //$etape_precedente = $EtapeRepository->getEtapeByExerciceAndNumEtape($exercice, $numeroEtape - 1);
                        $exercice->setEtapeCourante($etapeCourante);
                        // on met a jour l'etape courante (mauvaise réponse)
                        $etapeCourante->setBonneReponse(false);
                        $em->persist($etapeCourante);
                        $em->flush();
                        // on met a jour l'exercice en cours
                        $em->persist($exercice);
                        $em->flush();
                        $arrayMultimedia = array();
                        // on recupere le multimedia de l'etape suivante
                        $multimedia = $etapeCourante->getMultimedia();
                        array_push($arrayMultimedia, $multimedia);
                        $session->set('AfficherToutesEtapes', true);
                    }
                    // si on est a l'etape 1, on reste a l'etape 1 (on peut pas aller plus bas)
                    if ($numeroEtape == 1) {
                        // on recupere l'etape précédente
                        $etape_precedente = $EtapeRepository->getEtapeByExerciceAndNumEtape($exercice, $numeroEtape);
                        $exercice->setEtapeCourante($etape_precedente);
                        // on met a jour l'etape courante (mauvaise réponse)
                        $etapeCourante->setBonneReponse(false);
                        $em->persist($etapeCourante);
                        $em->flush();
                        // on met a jour l'exercice en cours
                        $em->persist($exercice);
                        $em->flush();
                        $arrayMultimedia = array();
                        // on recupere le multimedia de l'etape suivante
                        $multimedia = $etape_precedente->getMultimedia();
                        array_push($arrayMultimedia, $multimedia);
                        $session->set('AfficherToutesEtapes', false);
                    }
                }
                
                return $this->render('UPONDOrthophonieBundle:Exercice:exercice.html.twig', array('multimedias' => $arrayMultimedia, 'afficherSon' => $session->get('afficherSon'), 'form' => $form->createView()));
            }
        }

        return $this->render('UPONDOrthophonieBundle:Exercice:exercice.html.twig', array('multimedias' => $arrayMultimedia, 'afficherSon' => $session->get('afficherSon'), 'form' => $form->createView()));
    }

}