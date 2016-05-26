<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 19/05/2016
 * Time: 14:11
 */

namespace UPOND\OrthophonieBundle\Tests\Controller;

use UPOND\OrthophonieBundle\Controller\PartieController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class PartieControllerTest extends WebTestCase
{
    private $client = null;

    public function testStartAction(){
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

        $partieController = new PartieController();
        $em = $partieController->getDoctrine()->getManager();

        $PartieRepository = $em->getRepository('UPONDOrthophonieBundle:Partie');
        $listPartiesAvant = $PartieRepository->findAll();
        $i = 0;
        foreach ($listPartiesAvant as $partie){
            $i = $i + 1;
        }

        $crawler = $this->client->request('POST', '/start');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $PartieRepository = $em->getRepository('UPONDOrthophonieBundle:Partie');
        $listPartiesApres = $PartieRepository->findAll();

        $j = 0;
        foreach ($listPartiesApres as $partie2){
            $j = $j + 1;
        }
        $this->assertGreaterThan($i, $j);
    }
}