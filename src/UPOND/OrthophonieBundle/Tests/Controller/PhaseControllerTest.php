<?php
/**
 * Created by PhpStorm.
 * User: davidlou
 * Date: 25/05/2016
 * Time: 23:36
 */

namespace UPOND\OrthophonieBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhaseControllerTest extends WebTestCase
{

    public function testStatsAction()
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
        $client->request('POST', '/stats');

        $this->assertContains('Score', $client->getResponse()->getContent());
    }

}