<?php
/**
 * Created by PhpStorm.
 * User: davidlou
 * Date: 25/05/2016
 * Time: 23:28
 */

namespace UPOND\OrthophonieBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use UPOND\OrthophonieBundle\Entity\Utilisateur;

class ConnexionControllerTest extends WebTestCase
{

    public function testConnexionAction()
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
    }

}
