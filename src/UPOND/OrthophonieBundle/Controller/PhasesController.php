<?php
/**
 * Created by PhpStorm.
 * User: davidlou
 * Date: 19/04/2016
 * Time: 23:23
 */

namespace UPOND\OrthophonieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UPOND\OrthophonieBundle\Entity\Exercice;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use UPOND\OrthophonieBundle\Entity\Partie;
use UPOND\OrthophonieBundle\Entity\Patient;


class PhasesController extends Controller
{

    public function indexAction()
    {
        return $this->render('UPONDOrthophonieBundle::index.html.twig');
    }

    public function phasesAction()
    {
        return $this->render('UPONDOrthophonieBundle:Phases:phases.html.twig');
    }

    public function apprentissageAction()
    {
        return $this->render('UPONDOrthophonieBundle:Phases:apprentissage.html.twig');
    }

    public function entrainementAction()
    {
        return $this->render('UPONDOrthophonieBundle:Phases:entrainement.html.twig');
    }

    public function transfertAction(Request $request)
    {

        return $this->render('UPONDOrthophonieBundle:Phases:transfert.html.twig');
    }

    public function statsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $utilisateur = $this->container->get('security.context')->getToken()->getUser();
        $patient = $em->getRepository(Patient::class)->findOneBy(['utilisateur' => $utilisateur]);
        $parties = $em->getRepository(Partie::class)->findBy(['patient' => $patient]);

        $query = $em->getRepository(Exercice::class)->createQueryBuilder('n');
        $exos = $query->where($query->expr()->in('n.partie', ':parties'))->setParameter('parties', $parties)->getQuery()->getResult();

        // Generation du tableau pour le graphe //
        $graph = [];
        foreach ($exos as $exo) {
            $time = $exo->getDateCreation()->getTimestamp();
            if(!array_key_exists($time, $graph)) {
                $graph[$time] = [$exo->getNbBonneReponse()];
            } else {
                array_push($graph[$time], $exo->getNbBonneReponse());
            }
        }
        $graph = array_map(function($o) {return array_sum($o) / count($o);}, $graph);

        return $this->render('UPONDOrthophonieBundle:Stats:stats.html.twig', 
            ['exercices' => $exos,
             'graph' => $graph]);
    }

    public function apprentissage_niveau1Action(Request $request)
    {
        $session = $request->getSession();
        // stocker une variable de session pour le niveau et la phase
        $em = $this
            ->getDoctrine()
            ->getManager();

        $repository = $em
            ->getRepository('UPONDOrthophonieBundle:Phase')
        ;
        $phase = $repository->findOneByNom($request->attributes->get('phase'));
        $session->set('phase', $phase);
        $session->set('niveau', $request->attributes->get('niveau'));
        $session->set('afficherSon', true);
        return $this->render('UPONDOrthophonieBundle:Strategie:strategie.html.twig');
    }

    public function apprentissage_niveau2Action(Request $request)
    {
        $session = $request->getSession();
        // stocker une variable de session pour le niveau et la phase
        $em = $this
            ->getDoctrine()
            ->getManager();

        $repository = $em
            ->getRepository('UPONDOrthophonieBundle:Phase')
        ;
        $phase = $repository->findOneByNom($request->attributes->get('phase'));
        $session->set('phase', $phase);
        $session->set('niveau', $request->attributes->get('niveau'));
        $session->set('afficherSon', false);
        return $this->render('UPONDOrthophonieBundle:Strategie:strategie.html.twig');
    }

    public function entrainement_niveau1Action(Request $request)
    {
        $session = $request->getSession();
        // stocker une variable de session pour le niveau et la phase
        $em = $this
            ->getDoctrine()
            ->getManager();

        $repository = $em
            ->getRepository('UPONDOrthophonieBundle:Phase')
        ;
        $phase = $repository->findOneByNom($request->attributes->get('phase'));
        $session->set('phase', $phase);
        $session->set('niveau', $request->attributes->get('niveau'));
        $session->set('afficherSon', false);
        return $this->render('UPONDOrthophonieBundle:Strategie:strategie.html.twig');
    }

    public function entrainement_niveau2Action(Request $request)
    {
        $session = $request->getSession();
        // stocker une variable de session pour le niveau et la phase
        $em = $this
            ->getDoctrine()
            ->getManager();

        $repository = $em
            ->getRepository('UPONDOrthophonieBundle:Phase')
        ;
        $phase = $repository->findOneByNom($request->attributes->get('phase'));
        $session->set('phase', $phase);
        $session->set('niveau', $request->attributes->get('niveau'));
        $session->set('afficherSon', false);
        return $this->render('UPONDOrthophonieBundle:Strategie:strategie.html.twig');
    }

    /**
     * @Route("/exercice", name="upond_orthophonie_exercice")
     */
    public function exerciceAction(Request $request)
    {
        // on récupere tous les ID de la table Question
        $em = $this
            ->getDoctrine()
            ->getManager();
        $query = $em->createQuery(
            'SELECT q.idQuestion
            FROM UPONDOrthophonieBundle:Question q'
        );

        $questions = $query->getResult();

        $repository = $em
            ->getRepository('UPONDOrthophonieBundle:Question')
        ;
        // on prend un id aléatoire parmi les résultats
        $idQuestion = array_rand($questions);
        // on récupere l'entité de l'ID
        $question = $repository->findOneByIdQuestion($idQuestion);
        // on récupere le multimedia associé
        $multimedia = $question->getMultimedia();

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

            // si c'est le bouton "Bonne réponse"
            if ($form->get('BonneReponse')->isClicked()) {
                $request->getSession()->getFlashBag()->add('reponse', 'Bonne réponse.');
            }

            // si c'est le bouton "Mauvaise réponse"
            if ($form->get('MauvaiseReponse')->isClicked()) {
                $request->getSession()->getFlashBag()->add('reponse', 'Mauvaise réponse.');
            }
        }
        
        return $this->render('UPONDOrthophonieBundle:Phases:exercice.html.twig', array('question' => $question, 'multimedia' => $multimedia, 'form' => $form->createView()));
    }
}