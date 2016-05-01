<?php
/**
 * Created by PhpStorm.
 * User: d0ud0o
 * Date: 28/04/2016
 * Time: 23:42
 */

namespace UPOND\OrthophonieBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdministrationController extends Controller
{
    public function patientsAction(){
        return $this->render('UPONDOrthophonieBundle:Administration:patients.html.twig');
    }

    public function medecinsAction(){
        return $this->render('UPONDOrthophonieBundle:Administration:medecins.html.twig');
    }

    public function exercicesAction(){
        return $this->render('UPONDOrthophonieBundle:Administration:exercices.html.twig');
    }
}