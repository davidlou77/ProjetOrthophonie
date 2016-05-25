<?php
/**
 * Created by PhpStorm.
 * User: davidlou
 * Date: 25/05/2016
 * Time: 23:40
 */

namespace UPOND\OrthophonieBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use UPOND\OrthophonieBundle\Controller\PartieController;

class ExerciceControllerTest extends WebTestCase
{

    public function testSelectPartieAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Connexion')->form(array(
            '_username'  => 'dupont.toto',
            '_password'  => 'dupont',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertContains('Toto Dupont', $client->getResponse()->getContent());
        $client->request('POST', '/select_partie/Apprentissage/1/Morphologie');

        $this->assertContains('Sélectionner la partie', $client->getResponse()->getContent());
    }

}