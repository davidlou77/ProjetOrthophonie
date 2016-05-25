<?php

namespace UPOND\OrthophonieBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use UPOND\OrthophonieBundle\Entity\Utilisateur;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        //$this->assertContains('Hello World', $client->getResponse()->getContent());
    }

    public function testConnexionAction()
    {
        $user = new Utilisateur();
        $result = $user->setNom("Test");

        $this->assertEquals("Test", $result->getNom());
    }

    public function testMedecinsAjoutAction()
    {
        $user = new Utilisateur();
        $result = $user->setNom("Test");

        $this->assertEquals("Test", $result->getNom());
    }
}
