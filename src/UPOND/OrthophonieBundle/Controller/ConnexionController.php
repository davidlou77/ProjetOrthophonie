<?php
namespace UPOND\OrthophonieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAware;
use UPOND\OrthophonieBundle\Entity\Utilisateur;
use UPOND\OrthophonieBundle\Form\NewUtilisateurForm;

class ConnexionController extends Controller
{

    public function connexionAction()
    {
        $user = new Utilisateur();
//        $form = $this->container->get('form.factory')->create(new NewUtilisateurForm(), $user);
        $form = $this->createForm(NewUtilisateurForm::class, $user);

        $request = $this->container->get('request');
        $message = 'Connexion non établie';

        if ($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);

            if ($form->isValid())
            {
                $l = $this->getDoctrine()->getRepository(Utilisateur::class)->findBy(array("login" => $user->getLogin()))[0];
                $message = 'Connexion réussie : ' . $l->getIdUtilisateur();
            }
        }

        return $this->container->get('templating')->renderResponse(
            'UPONDOrthophonieBundle:Connexion:connexion.html.twig',
            array(
                'form' => $form->createView(),
                'message' => $message ));
    }

}