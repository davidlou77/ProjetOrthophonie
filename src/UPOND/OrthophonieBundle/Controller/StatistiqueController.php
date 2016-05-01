<?php
/**
 * Created by PhpStorm.
 * User: d0ud0o
 * Date: 28/04/2016
 * Time: 22:21
 */

namespace UPOND\OrthophonieBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatistiqueController extends  Controller
{
    public function statistiqueAction(){
        return $this->render('UPONDOrthophonieBundle:Statistique:statistique.html.twig');
    }
}