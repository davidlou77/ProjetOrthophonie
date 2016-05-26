<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 19/05/2016
 * Time: 14:10
 */

namespace UPOND\OrthophonieBundle\Tests\Controller;

use UPOND\OrthophonieBundle\Controller\AdministrationController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use UPOND\OrthophonieBundle\Entity\Medecin;
use UPOND\OrthophonieBundle\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;


class AdministrationControllerTest extends WebTestCase
{
    
    private $client = null;
    
    public function testMedecinsAjoutAction(){
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

        $adminController = new AdministrationController();
        $em = $adminController->getDoctrine()->getManager();

        $MedecinRepository = $em->getRepository('UPONDOrthophonieBundle:Medecin');
        $listMedecinsAvant = $MedecinRepository->findAll();
        $i = 0;
        foreach ($listMedecinsAvant as $med){
            $i = $i + 1;
        }

        $crawler = $this->client->request('POST', '/ajout_medecin');
        
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $listMedecinsApres = $crawler->extract('listMedecins');
        $j = 0;
        foreach ($listMedecinsApres as $med2){
            $j = $j + 1;
        }
        $this->assertGreaterThan($i, $j);
    }

    public function testMedecinsRetirerAction(){
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

        $adminController = new AdministrationController();
        $em = $adminController->getDoctrine()->getManager();

        $MedecinRepository = $em->getRepository('UPONDOrthophonieBundle:Medecin');
        $listMedecinsAvant = $MedecinRepository->findAll();
        $i = 0;
        foreach ($listMedecinsAvant as $med){
            $i = $i + 1;
        }

        $crawler = $this->client->request('POST', '/retirer_medecin');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $listMedecinsApres = $crawler->extract('listMedecins');
        $j = 0;
        foreach ($listMedecinsApres as $med2){
            $j = $j + 1;
        }
        $this->assertGreaterThan($j, $i);
    }

}
