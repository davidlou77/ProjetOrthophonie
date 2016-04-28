<?php
/**
 * Created by PhpStorm.
 * User: davidlou
 * Date: 24/04/2016
 * Time: 14:55
 */

namespace UPOND\OrthophonieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UPOND\OrthophonieBundle\Entity\Exercice;
use UPOND\OrthophonieBundle\Entity\Multimedia;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use UPOND\OrthophonieBundle\Entity\Partie;


class PartieController extends Controller
{

    public function indexAction()
    {
        return $this->render('UPONDOrthophonieBundle::index.html.twig');
    }


    public function startAction(Request $request)
    {


        // On crée le FormBuilder grâce au service form factory
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class);

        // On ajoute les champs que l'on veut à notre formulaire
        $formBuilder
            ->add('TempsEntrainement', TimeType::class, array(
                'input' => 'datetime',
                'widget' => 'choice',
                'with_minutes' => 'true',
                'with_seconds' => 'true'))
            ->add('TempsTransfert', TimeType::class, array(
                'input' => 'datetime',
                'widget' => 'choice',
                'with_minutes' => 'true',
                'with_seconds' => 'true'))
            ->add('Créer une partie', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-success')));

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();

        if ($form->handleRequest($request)->isValid()) {


            //transformer la saisie heure/min/secondes en secondes uniquement
            $donneesForm = $form->getData();
            sscanf($donneesForm['TempsEntrainement']->format('H:i:s'), "%d:%d:%d", $hours, $minutes, $seconds);

            $time_seconds_entrainement = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;

            sscanf($donneesForm['TempsTransfert']->format('H:i:s'), "%d:%d:%d", $hours, $minutes, $seconds);

            $time_seconds_transfert = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;



            // on récupere l'id de l'user
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $idUser = $user->getId();

            // on récupère l'entité du patient selon l'id utilisateur
            $em = $this
                ->getDoctrine()
                ->getManager();

            $repositoryPatient = $em
                ->getRepository('UPONDOrthophonieBundle:Patient');
            // on récupere l'entité de l'ID
            $patient = $repositoryPatient->findOneByIdUtilisateur($idUser);

            // on créé une nouvelle partie
            $partie = new Partie();
            $partie->setDateCreation(new \DateTime());
            $partie->setIdPatient($patient);
            $partie->setPartieFini(false);

            // on ajoute la partie dans la bdd
            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();

            // on va créer des exercices et les lier à la partie

            //phase d'apprentissage
            $phase = "Apprentissage";
            //strategie: Metiers
            $strategie = "Metiers";

            $exerciceApprentissage = new Exercice();
            $exerciceApprentissage = $this->initializeExerciceApprentissage($exerciceApprentissage, $phase, $strategie, $partie);

            $em->persist($exerciceApprentissage);
            $em->flush();
            //strategie: Morphologie
            $strategie = "Morphologie";

            $exerciceApprentissage = new Exercice();
            $exerciceApprentissage = $this->initializeExerciceApprentissage($exerciceApprentissage, $phase, $strategie, $partie);

            $em->persist($exerciceApprentissage);
            $em->flush();
            //strategie: Morphologie_inverse
            $strategie = "Morphologie_inverse";

            $exerciceApprentissage = new Exercice();
            $exerciceApprentissage = $this->initializeExerciceApprentissage($exerciceApprentissage, $phase, $strategie, $partie);

            $em->persist($exerciceApprentissage);
            $em->flush();

            // autres stratégies ...

            //phase d'entrainement, on recupere les questions de la phase d'apprentissage
            $phase = "Entrainement";
            //strategie: Metiers
            $strategie = "Metiers";

            $exerciceEntrainement = new Exercice();
            $exerciceEntrainement = $this->initializeExerciceEntrainement($exerciceEntrainement, $phase, $strategie, $partie, $time_seconds_entrainement);

            $em->persist($exerciceEntrainement);
            $em->flush();

            //strategie: Morphologie
            $strategie = "Morphologie";

            $exerciceEntrainement = new Exercice();
            $exerciceEntrainement = $this->initializeExerciceEntrainement($exerciceEntrainement, $phase, $strategie, $partie, $time_seconds_entrainement);

            $em->persist($exerciceEntrainement);
            $em->flush();

            //strategie: Morphologie_inverse
            $strategie = "Morphologie_inverse";

            $exerciceEntrainement = new Exercice();
            $exerciceEntrainement = $this->initializeExerciceEntrainement($exerciceEntrainement, $phase, $strategie, $partie, $time_seconds_entrainement);

            $em->persist($exerciceEntrainement);
            $em->flush();

            // autres stratégies ...
            
            
            // faire une phase d'entrainement en mélangeant les stratégies

            $strategie = "Random";

            $exerciceEntrainement = new Exercice();
            $exerciceEntrainement = $this->initializeExerciceEntrainementAleatoire($exerciceEntrainement, $phase, $strategie, $partie, $time_seconds_entrainement);

            $em->persist($exerciceEntrainement);
            $em->flush();

            // phase de transfert
            $phase = "Transfert";
            // strategie random
            $strategie = "Random";

            $exerciceTransfert = new Exercice();
            $exerciceTransfert = $this->initializeExerciceTransfert($exerciceTransfert, $phase, $strategie, $partie, $time_seconds_transfert);

            $em->persist($exerciceTransfert);
            $em->flush();





            return $this->redirect($this->generateUrl('upond_orthophonie_phases'));
        }

        // À ce stade :
        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau
        return $this->render('UPONDOrthophonieBundle:Partie:partie.html.twig', array(
            'form' => $form->createView(),
        ));

    }


    public function get7RandomQuestions($strategie)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();
        $queryQuestions = $em->createQuery(
            'SELECT q.idQuestion
            FROM UPONDOrthophonieBundle:Question q
            JOIN UPONDOrthophonieBundle:Multimedia m
            WHERE q.idQuestion = m.id AND m.type = :strategie'
        );
        $queryQuestions->setParameter('strategie', $strategie);

        $questions = $queryQuestions->getResult();

       
        // on prend un id aléatoire parmi les résultats
        $idQuestion = array_rand($questions, 7);
        $arrayQuestion = array();
        foreach($idQuestion as $element)
        {
            $arrayQuestion[] = $questions[$element];
        }

        // on récupere l'entité de l'ID
        $queryQuestions = $em->createQuery(
            'SELECT q
            FROM UPONDOrthophonieBundle:Question q
            WHERE q.idQuestion IN (:arrayIdQuestions)'
        );
        $queryQuestions->setParameter('arrayIdQuestions', $arrayQuestion);
        $listQuestions = $queryQuestions->getResult();

        //$listQuestions = $repositoryQuestion->findByIdQuestion($arrayQuestion);
        return $listQuestions;
    }

    public function getExerciceByStrategieAndPartie($strategie, $idPartie)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();
        $queryExercice = $em->createQuery(
            'SELECT e.idExercice
            FROM UPONDOrthophonieBundle:Exercice e
            WHERE e.partie = :idPartie AND e.strategie = :strategie'
        );
        $queryExercice->setParameters(array('strategie' => $strategie,
            'idPartie' => $idPartie));

        $idExercice = $queryExercice->getSingleResult();

        $repositoryExercice = $em
            ->getRepository('UPONDOrthophonieBundle:Exercice')
        ;

        // on récupere l'entité de l'ID
        $exercice = $repositoryExercice->findOneByIdExercice($idExercice);
        return $exercice;
    }

    public function initializeExerciceApprentissage($exercice, $phase, $strategie, $partie)
    {
        $exercice->setExerciceFini(false);
        $exercice->setNbBonneReponse(0);
        $exercice->setPartie($partie);
        $exercice->setStrategie($strategie);
        $exercice->setPhase($phase);
        $exercice->setTauxReussite(0);

        $listQuestions = $this->get7RandomQuestions($strategie);
        foreach($listQuestions as $question)
        {
            $exercice->addQuestion($question);
        }
        return $exercice;
    }

    public function initializeExerciceEntrainement($exercice, $phase, $strategie, $partie, $temps)
    {
        $exercice->setExerciceFini(false);
        $exercice->setNbBonneReponse(0);
        $exercice->setPartie($partie);
        $exercice->setStrategie($strategie);
        $exercice->setPhase($phase);
        $exercice->setTauxReussite(0);
        $exercice->setTemps($temps);

        $exerciceTemporaire = $this->getExerciceByStrategieAndPartie($strategie, $partie->getIdPartie());

        foreach($exerciceTemporaire->getQuestions() as $question)
        {
            $exercice->addQuestion($question);
        }
        return $exercice;
    }

    public function getIdQuestionsFromPhase($partie, $phase)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();
        $queryExercice = $em->createQuery(
            'SELECT q.idQuestion
            FROM UPONDOrthophonieBundle:Exercice e
            JOIN e.questions q
            WHERE e.partie = :idPartie AND e.phase = :phase
            GROUP BY q.idQuestion'
        );
        $queryExercice->setParameters(array('idPartie' => $partie,
            'phase' => $phase));

        $idQuestions = $queryExercice->getResult();
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
        return $idQuestions;
    }

    public function initializeExerciceEntrainementAleatoire($exercice, $phase, $strategie, $partie, $temps)
    {
        $exercice->setExerciceFini(false);
        $exercice->setNbBonneReponse(0);
        $exercice->setPartie($partie);
        $exercice->setStrategie($strategie);
        $exercice->setPhase($phase);
        $exercice->setTauxReussite(0);
        $exercice->setTemps($temps);

        $questions = $this->getIdQuestionsFromPhase($partie->getIdPartie(), "Apprentissage");

        // on prend un id aléatoire parmi les résultats
        $idQuestion = array_rand($questions, 7);
        $arrayQuestion = array();
        foreach($idQuestion as $element)
        {
            $arrayQuestion[] = $questions[$element];
        }

        $em = $this
            ->getDoctrine()
            ->getManager();

        // on récupere l'entité de l'ID
        $queryQuestions = $em->createQuery(
            'SELECT q
            FROM UPONDOrthophonieBundle:Question q
            WHERE q.idQuestion IN (:arrayIdQuestions)'
        );
        $queryQuestions->setParameter('arrayIdQuestions', $arrayQuestion);
        $listQuestions = $queryQuestions->getResult();

        foreach($listQuestions as $question)
        {
            $exercice->addQuestion($question);
        }

        return $exercice;
    }

    public function initializeExerciceTransfert($exercice, $phase, $strategie, $partie, $temps)
    {
        $exercice->setExerciceFini(false);
        $exercice->setNbBonneReponse(0);
        $exercice->setPartie($partie);
        $exercice->setStrategie($strategie);
        $exercice->setPhase($phase);
        $exercice->setTauxReussite(0);
        $exercice->setTemps($temps);

        $questions = $this->getIdQuestionsFromPhase($partie->getIdPartie(), "Apprentissage");

        $em = $this
            ->getDoctrine()
            ->getManager();

        // on récupere l'entité de l'ID
        $queryQuestions = $em->createQuery(
            'SELECT q
            FROM UPONDOrthophonieBundle:Question q
            WHERE q.idQuestion NOT IN (:arrayIdQuestions)'
        );
        $queryQuestions->setParameter('arrayIdQuestions', $questions);
        $listQuestions = $queryQuestions->getResult();

        // on prend un id aléatoire parmi les résultats
        $idQuestionRandom = array_rand($listQuestions, 7);
        $arrayQuestion = array();
        foreach($idQuestionRandom as $element)
        {
            $arrayQuestion[] = $listQuestions[$element];
        }

        // on récupere l'entité de l'ID
        $queryQuestions = $em->createQuery(
            'SELECT q
            FROM UPONDOrthophonieBundle:Question q
            WHERE q.idQuestion IN (:arrayIdQuestions)'
        );
        $queryQuestions->setParameter('arrayIdQuestions', $arrayQuestion);
        $listQuestionsRandom = $queryQuestions->getResult();

        foreach($listQuestionsRandom as $question)
        {
            $exercice->addQuestion($question);
        }

        return $exercice;
    }
}