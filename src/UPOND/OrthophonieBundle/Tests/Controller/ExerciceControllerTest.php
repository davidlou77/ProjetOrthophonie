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
    public function testAfficherExerciceAction(){
        $this->client = static::createClient();
        $session = $this->client->getContainer()->get('session');

        $firewall = 'secured_area';
        $token = new UsernamePasswordToken('brunoehre', 'miage', $firewall, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);

        $crawler = $this->client->request('POST', '/login');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $crawler = $this->client->request('POST', '/exercice');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $listMultimedias = $crawler->extract('multimedias');
        $j = 0;
        foreach ($listMultimedias as $mul){
            $j = $j + 1;
        }
        $this->assertGreaterThan(0, $j);
    }
}